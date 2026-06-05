<?php

declare(strict_types=1);

/**
 * Speedy SDK code generator.
 *
 * Reads:
 *   - {schemaRoot}/*.schema.json   (snapshot of https://api.speedy.bg/v1/schema)
 *   - {catalogPath}                (hand-curated operation catalog)
 *
 * Emits:
 *   - {srcRoot}/Dto/Model/*.php
 *   - {srcRoot}/Dto/Request/{Group}/*Request.php
 *   - {srcRoot}/Dto/Response/{Group}/*Response.php
 *   - {srcRoot}/Resources/{Group}.php
 *   - {srcRoot}/Speedy.php (resource accessors only — markers preserved)
 *   - {srcRoot}/Laravel/Facades/Speedy.php (@method annotations only)
 */

namespace Ux2Dev\Speedy\Generator;

use RuntimeException;

const NS_REQUEST  = 'Ux2Dev\\Speedy\\Dto\\Request';
const NS_RESPONSE = 'Ux2Dev\\Speedy\\Dto\\Response';
const NS_MODEL    = 'Ux2Dev\\Speedy\\Dto\\Model';

/** Auth fields auto-injected by SpeedyTransport — stripped from generated request DTOs. */
const STRIPPED_REQUEST_FIELDS = ['userName', 'password', 'language', 'clientSystemId'];

final class Generator
{
    /** @var array<string, true> Names of top-level string-enum schemas; populated in run(). */
    public static array $stringEnums = [];

    /** @var array<string, true> Names of every schema present in the bundle; populated in run(). */
    public static array $knownSchemas = [];

    public function __construct(
        public readonly string $srcRoot,
        public readonly string $schemaRoot,
        public readonly string $catalogPath,
    ) {
    }

    /**
     * @param array<string, array<string, mixed>> $allSchemas
     * @return array<string, true>
     */
    private static function detectStringEnums(array $allSchemas): array
    {
        $out = [];
        foreach ($allSchemas as $name => $schema) {
            if (
                ($schema['type'] ?? null) === 'string'
                && isset($schema['enum'])
                && is_array($schema['enum'])
            ) {
                $out[$name] = true;
            }
        }
        return $out;
    }

    public function requestRoot(): string   { return $this->srcRoot . '/Dto/Request'; }
    public function responseRoot(): string  { return $this->srcRoot . '/Dto/Response'; }
    public function modelRoot(): string     { return $this->srcRoot . '/Dto/Model'; }
    public function resourcesRoot(): string { return $this->srcRoot . '/Resources'; }
    public function speedyFile(): string    { return $this->srcRoot . '/Speedy.php'; }
    public function facadeFile(): string    { return $this->srcRoot . '/Laravel/Facades/Speedy.php'; }

    public function run(): void
    {
        if (! file_exists($this->catalogPath)) {
            throw new RuntimeException("Endpoints catalog not found at {$this->catalogPath}");
        }
        $catalog = json_decode((string) file_get_contents($this->catalogPath), true, flags: JSON_THROW_ON_ERROR);
        if (! is_array($catalog)) {
            throw new RuntimeException('endpoints.json must be a JSON array');
        }

        $allSchemas         = $this->loadSchemas();
        self::$stringEnums  = self::detectStringEnums($allSchemas);
        self::$knownSchemas = array_fill_keys(array_keys($allSchemas), true);

        // Wipe outputs (preserve hand-written Resource.php base).
        rmdirRecursive($this->requestRoot());
        rmdirRecursive($this->responseRoot());
        rmdirRecursive($this->modelRoot());
        foreach (glob($this->resourcesRoot() . '/*.php') ?: [] as $f) {
            if (basename($f) !== 'Resource.php') {
                unlink($f);
            }
        }
        ensureDir($this->requestRoot());
        ensureDir($this->responseRoot());
        ensureDir($this->modelRoot());

        /** @var array<string, list<array<string, mixed>>> */
        $byGroup = [];
        /** @var array<string, true> */
        $modelsToEmit = [];

        foreach ($catalog as $entry) {
            $group   = (string) $entry['group'];
            $returns = (string) ($entry['returns'] ?? 'json');
            $byGroup[$group][] = $entry;

            // CSV bulk endpoints take only path params (no JSON request DTO, no JSON response DTO).
            if ($returns === 'csv') {
                continue;
            }

            $reqClass = (string) $entry['request'];
            if (! isset($allSchemas[$reqClass])) {
                throw new RuntimeException("Schema {$reqClass} not found in {$this->schemaRoot}");
            }
            $this->emitRequestDto($group, $reqClass, $allSchemas[$reqClass], $modelsToEmit);

            if ($returns === 'json') {
                $respClass = (string) $entry['response'];
                if (! isset($allSchemas[$respClass])) {
                    throw new RuntimeException("Schema {$respClass} not found in {$this->schemaRoot}");
                }
                $this->emitResponseDto($group, $respClass, $allSchemas[$respClass], $modelsToEmit);
            }
        }

        $this->emitModels($modelsToEmit, $allSchemas);

        foreach ($byGroup as $group => $methods) {
            $code = $this->renderResource($group, $methods);
            writeFile($this->resourcesRoot() . '/' . $group . '.php', $code);
        }

        $groups = array_keys($byGroup);
        sort($groups);
        $this->rewriteSpeedyRoot($groups);
        $this->rewriteFacadeAnnotations($groups);

        echo "Generated " . count($catalog) . " operations across " . count($groups) . " groups.\n";
    }

    /** @return array<string, array<string, mixed>> */
    private function loadSchemas(): array
    {
        $out = [];
        foreach (glob($this->schemaRoot . '/*.schema.json') ?: [] as $path) {
            $name = basename($path, '.schema.json');
            $data = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
            if (! is_array($data)) {
                throw new RuntimeException("Schema {$path} did not parse to an object");
            }
            $out[$name] = $data;
        }
        return $out;
    }

    /**
     * @param array<string, mixed> $prop
     * @param array<string, true>  $modelsOut accumulator (by reference)
     * @return array{0: string, 1: string, 2: string}  [phpType, fromArrayExpr, toArrayStmt]
     */
    public static function mapProperty(string $key, array $prop, array &$modelsOut): array
    {
        $keyLit = var_export($key, true);
        $php    = camel($key);

        if (isset($prop['$ref']) && is_string($prop['$ref'])) {
            $model = urnToSimpleName($prop['$ref']);
            // Speedy ships several $refs whose schema is never published (e.g. OfficeRoutingInformation);
            // keep the payload as a raw array so the SDK doesn't crash when the API actually returns it.
            if (! isset(self::$knownSchemas[$model])) {
                $type = '?array';
                $from = "isset(\$data[{$keyLit}]) && is_array(\$data[{$keyLit}]) ? \$data[{$keyLit}] : null";
                $to   = "if (\$this->{$php} !== null) \$out[{$keyLit}] = \$this->{$php};";
                return [$type, $from, $to];
            }
            $modelsOut[$model] = true;
            $type = '?\\' . NS_MODEL . '\\' . $model;
            if (self::$stringEnums[$model] ?? false) {
                $from = "isset(\$data[{$keyLit}]) && is_string(\$data[{$keyLit}]) ? \\" . NS_MODEL . "\\{$model}::tryFrom(\$data[{$keyLit}]) : null";
                $to   = "if (\$this->{$php} !== null) \$out[{$keyLit}] = \$this->{$php}->value;";
            } else {
                $from = "isset(\$data[{$keyLit}]) && is_array(\$data[{$keyLit}]) ? \\" . NS_MODEL . "\\{$model}::fromArray(\$data[{$keyLit}]) : null";
                $to   = "if (\$this->{$php} !== null) \$out[{$keyLit}] = \$this->{$php}->toArray();";
            }
            return [$type, $from, $to];
        }

        $type = $prop['type'] ?? 'string';

        if ($type === 'array') {
            $items = $prop['items'] ?? [];
            if (is_array($items) && isset($items['$ref']) && is_string($items['$ref'])) {
                $model = urnToSimpleName($items['$ref']);
                if (! isset(self::$knownSchemas[$model])) {
                    $type_ = '?array';
                    $from  = "isset(\$data[{$keyLit}]) && is_array(\$data[{$keyLit}]) ? \$data[{$keyLit}] : null";
                    $to    = "if (\$this->{$php} !== null) \$out[{$keyLit}] = \$this->{$php};";
                    return [$type_, $from, $to];
                }
                $modelsOut[$model] = true;
                $type_ = '?array';
                if (self::$stringEnums[$model] ?? false) {
                    $from = "isset(\$data[{$keyLit}]) && is_array(\$data[{$keyLit}]) ? array_map(fn(string \$r) => \\" . NS_MODEL . "\\{$model}::tryFrom(\$r), \$data[{$keyLit}]) : null";
                    $to   = "if (\$this->{$php} !== null) \$out[{$keyLit}] = array_map(fn(\\" . NS_MODEL . "\\{$model} \$x) => \$x->value, \$this->{$php});";
                } else {
                    $from = "isset(\$data[{$keyLit}]) && is_array(\$data[{$keyLit}]) ? array_map(fn(array \$r) => \\" . NS_MODEL . "\\{$model}::fromArray(\$r), \$data[{$keyLit}]) : null";
                    $to   = "if (\$this->{$php} !== null) \$out[{$keyLit}] = array_map(fn(\\" . NS_MODEL . "\\{$model} \$x) => \$x->toArray(), \$this->{$php});";
                }
                return [$type_, $from, $to];
            }
            $type_ = '?array';
            $from  = "isset(\$data[{$keyLit}]) && is_array(\$data[{$keyLit}]) ? \$data[{$keyLit}] : null";
            $to    = "if (\$this->{$php} !== null) \$out[{$keyLit}] = \$this->{$php};";
            return [$type_, $from, $to];
        }

        $phpScalar = match ($type) {
            'integer' => 'int',
            'number'  => 'float',
            'boolean' => 'bool',
            'string'  => 'string',
            default   => 'mixed',
        };
        $type_ = $phpScalar === 'mixed' ? 'mixed' : '?' . $phpScalar;
        $from  = "\$data[{$keyLit}] ?? null";
        $to    = "if (\$this->{$php} !== null) \$out[{$keyLit}] = \$this->{$php};";
        return [$type_, $from, $to];
    }

    // -----------------------------------------------------------------------
    // Model DTO emission

    /**
     * @param array<string, mixed> $schema
     * @return array{code: string, refs: array<string, true>}
     */
    public static function renderModelDto(string $name, array $schema): array
    {
        $refs = [];
        $properties = $schema['properties'] ?? [];
        $ctorParams = [];
        $fromArray  = [];
        $toArray    = [];

        foreach ($properties as $key => $prop) {
            if (! is_string($key) || ! preg_match('~^[A-Za-z_][A-Za-z0-9_]*$~', $key)) {
                continue;
            }
            if (! is_array($prop)) {
                continue;
            }
            [$type, $from, $to] = self::mapProperty($key, $prop, $refs);
            $php = camel($key);
            $ctorParams[] = "        public readonly {$type} \${$php} = null,";
            $fromArray[]  = "            {$php}: {$from},";
            $toArray[]    = "        {$to}";
        }

        if ($ctorParams === []) {
            $ctorParams[] = '        // (schema declared no scalar properties)';
            $fromArray[]  = '';
            $toArray[]    = '';
        }

        $ctorBlock = implode("\n", $ctorParams);
        $fromBlock = implode("\n", $fromArray);
        $toBlock   = implode("\n", $toArray);

        $code = <<<PHP
<?php

declare(strict_types=1);

namespace Ux2Dev\\Speedy\\Dto\\Model;

final class {$name}
{
    public function __construct(
{$ctorBlock}
    ) {
    }

    /** @param array<string, mixed> \$data */
    public static function fromArray(array \$data): self
    {
        return new self(
{$fromBlock}
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        \$out = [];
{$toBlock}
        return \$out;
    }
}
PHP;

        return ['code' => $code, 'refs' => $refs];
    }

    /**
     * @param array<string, true> $seedNames
     * @param array<string, array<string, mixed>> $allSchemas
     */
    private function emitModels(array $seedNames, array $allSchemas): void
    {
        $queue   = $seedNames;
        $emitted = [];

        while ($queue !== []) {
            $name = array_key_first($queue);
            unset($queue[$name]);

            if (isset($emitted[$name])) {
                continue;
            }
            if (! isset($allSchemas[$name])) {
                continue;
            }

            if (self::$stringEnums[$name] ?? false) {
                $code = self::renderStringEnum($name, $allSchemas[$name]);
                writeFile($this->modelRoot() . '/' . $name . '.php', $code);
                $emitted[$name] = true;
                continue;
            }

            $rendered = self::renderModelDto($name, $allSchemas[$name]);
            writeFile($this->modelRoot() . '/' . $name . '.php', $rendered['code']);
            $emitted[$name] = true;

            foreach ($rendered['refs'] as $ref => $_) {
                if (! isset($emitted[$ref])) {
                    $queue[$ref] = true;
                }
            }
        }
    }

    /** @param array<string, mixed> $schema */
    public static function renderStringEnum(string $name, array $schema): string
    {
        $cases = [];
        foreach ((array) ($schema['enum'] ?? []) as $value) {
            if (! is_string($value)) continue;
            // PHP enum cases must be valid identifiers — Speedy's enum values are already screaming-snake-case.
            $cases[] = "    case {$value} = '{$value}';";
        }
        $body = implode("\n", $cases);

        return <<<PHP
<?php

declare(strict_types=1);

namespace Ux2Dev\\Speedy\\Dto\\Model;

enum {$name}: string
{
{$body}
}

PHP;
    }

    // -----------------------------------------------------------------------
    // Request DTO emission

    /**
     * @param array<string, mixed> $schema
     * @param array<string, true>  $modelsOut
     */
    private function emitRequestDto(string $group, string $className, array $schema, array &$modelsOut): void
    {
        $properties = $schema['properties'] ?? [];
        $ctor       = [];
        $toArray    = [];

        foreach ($properties as $key => $prop) {
            if (! is_string($key) || ! preg_match('~^[A-Za-z_][A-Za-z0-9_]*$~', $key)) {
                continue;
            }
            if (in_array($key, STRIPPED_REQUEST_FIELDS, true)) {
                continue;
            }
            if (! is_array($prop)) {
                continue;
            }
            [$type, $_from, $to] = self::mapProperty($key, $prop, $modelsOut);
            $php       = camel($key);
            $ctor[]    = "        public readonly {$type} \${$php} = null,";
            $toArray[] = "        {$to}";
        }

        if ($ctor === []) {
            $ctor[]    = '        // (schema declared no request properties beyond auth fields)';
            $toArray[] = '';
        }

        $ctorBlock    = implode("\n", $ctor);
        $toArrayBlock = implode("\n", $toArray);
        $namespace    = NS_REQUEST . '\\' . $group;

        $code = <<<PHP
<?php

declare(strict_types=1);

namespace {$namespace};

final readonly class {$className}
{
    public function __construct(
{$ctorBlock}
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        \$out = [];
{$toArrayBlock}
        return \$out;
    }
}
PHP;

        writeFile($this->requestRoot() . '/' . $group . '/' . $className . '.php', $code);
    }

    // -----------------------------------------------------------------------
    // Response DTO emission

    /**
     * @param array<string, mixed> $schema
     * @param array<string, true>  $modelsOut
     */
    private function emitResponseDto(string $group, string $className, array $schema, array &$modelsOut): void
    {
        $properties = $schema['properties'] ?? [];
        $ctor       = [];
        $fromArray  = [];

        foreach ($properties as $key => $prop) {
            if (! is_string($key) || ! preg_match('~^[A-Za-z_][A-Za-z0-9_]*$~', $key)) {
                continue;
            }
            if (! is_array($prop)) {
                continue;
            }
            [$type, $from, $_to] = self::mapProperty($key, $prop, $modelsOut);
            $php          = camel($key);
            $ctor[]       = "        public readonly {$type} \${$php} = null,";
            $fromArray[]  = "            {$php}: {$from},";
        }

        if ($ctor === []) {
            $code = self::renderGenericResponseDto($group, $className);
            writeFile($this->responseRoot() . '/' . $group . '/' . $className . '.php', $code);
            return;
        }

        $ctorBlock      = implode("\n", $ctor);
        $fromArrayBlock = implode("\n", $fromArray);
        $namespace      = NS_RESPONSE . '\\' . $group;

        $code = <<<PHP
<?php

declare(strict_types=1);

namespace {$namespace};

final class {$className}
{
    public function __construct(
{$ctorBlock}
    ) {
    }

    /** @param array<string, mixed> \$data */
    public static function fromArray(array \$data): self
    {
        return new self(
{$fromArrayBlock}
        );
    }
}
PHP;

        writeFile($this->responseRoot() . '/' . $group . '/' . $className . '.php', $code);
    }

    public static function renderGenericResponseDto(string $group, string $className): string
    {
        $namespace = NS_RESPONSE . '\\' . $group;
        return <<<PHP
<?php

declare(strict_types=1);

namespace {$namespace};

/**
 * Schema declared no documented response properties — raw payload kept verbatim.
 */
final class {$className}
{
    /** @param array<string, mixed> \$data */
    public function __construct(public readonly array \$data)
    {
    }

    /** @param array<string, mixed> \$data */
    public static function fromArray(array \$data): self
    {
        return new self(\$data);
    }
}
PHP;
    }

    // -----------------------------------------------------------------------
    // Resource emission + Speedy root + Facade rewrite

    /** @param list<array<string, mixed>> $methods */
    public static function renderResource(string $group, array $methods): string
    {
        $uses = [
            'Ux2Dev\\Speedy\\Resources\\Resource' => true,
        ];
        $methodCode = [];

        foreach ($methods as $m) {
            $name    = $m['name'];
            $path    = $m['path'];
            $method  = strtoupper($m['method']);
            $returns = $m['returns'] ?? 'json';

            if ($returns === 'csv') {
                $pathParams = $m['pathParams'] ?? [];
                $signature  = [];
                $pathExpr   = "'{$path}'";
                foreach ($pathParams as $p) {
                    $signature[] = "int \${$p}";
                    $pathExpr   .= " . '/' . \${$p}";
                }
                $signature[] = '?string $language = null';
                $signature[] = '?int $clientSystemId = null';
                $sig = implode(', ', $signature);

                $methodCode[] = <<<PHP
    public function {$name}({$sig}): string
    {
        \$body = [];
        if (\$language !== null) \$body['language'] = \$language;
        if (\$clientSystemId !== null) \$body['clientSystemId'] = \$clientSystemId;

        return \$this->transport->postCsv({$pathExpr}, \$body);
    }
PHP;
                continue;
            }

            $reqClass = $m['request'];
            $reqFqn   = NS_REQUEST . '\\' . $group . '\\' . $reqClass;
            $uses[$reqFqn] = true;

            if ($returns === 'bytes') {
                $uses['Ux2Dev\\Speedy\\Http\\PrintResult'] = true;
                $methodCode[] = <<<PHP
    public function {$name}({$reqClass} \$request, ?string \$language = null, ?int \$clientSystemId = null): PrintResult
    {
        \$body = \$request->toArray();
        if (\$language !== null) \$body['language'] = \$language;
        if (\$clientSystemId !== null) \$body['clientSystemId'] = \$clientSystemId;

        return \$this->transport->postBinary('{$path}', \$body);
    }
PHP;
                continue;
            }

            $respClass = $m['response'];
            $respFqn   = NS_RESPONSE . '\\' . $group . '\\' . $respClass;
            $uses[$respFqn] = true;

            $transportCall = match ($method) {
                'POST'   => 'postJson',
                'GET'    => 'getJson',
                'DELETE' => 'deleteJson',
                default  => throw new RuntimeException("Unsupported method {$method} for {$group}::{$name}"),
            };

            $methodCode[] = <<<PHP
    public function {$name}({$reqClass} \$request, ?string \$language = null, ?int \$clientSystemId = null): {$respClass}
    {
        \$body = \$request->toArray();
        if (\$language !== null) \$body['language'] = \$language;
        if (\$clientSystemId !== null) \$body['clientSystemId'] = \$clientSystemId;

        return \$this->transport->{$transportCall}('{$path}', \$body, {$respClass}::class);
    }
PHP;
        }

        ksort($uses);
        $useLines = array_map(fn(string $fqn) => "use {$fqn};", array_keys($uses));
        $useBlock = implode("\n", $useLines);
        $body     = implode("\n\n", $methodCode);

        return <<<PHP
<?php

declare(strict_types=1);

namespace Ux2Dev\\Speedy\\Resources;

{$useBlock}

final class {$group} extends Resource
{
{$body}
}
PHP;
    }

    /** Map a group name to its accessor name on the root client / facade. */
    public static function groupAccessor(string $group): string
    {
        return $group === 'PrintService' ? 'print' : lcfirst($group);
    }

    /** @param list<string> $groups */
    private function rewriteSpeedyRoot(array $groups): void
    {
        $properties = [];
        $accessors  = [];
        foreach ($groups as $g) {
            $accessor = self::groupAccessor($g);
            $properties[] = "private ?\\Ux2Dev\\Speedy\\Resources\\{$g} \${$accessor} = null;";
            $accessors[]  = <<<PHP
    public function {$accessor}(): \\Ux2Dev\\Speedy\\Resources\\{$g}
    {
        return \$this->{$accessor} ??= new \\Ux2Dev\\Speedy\\Resources\\{$g}(\$this->transport);
    }
PHP;
        }

        $contents = (string) file_get_contents($this->speedyFile());
        $propsBlock = '    ' . implode("\n    ", $properties);
        $contents = replaceMarkedBlock($contents, 'properties', $propsBlock);
        $contents = replaceMarkedBlock($contents, 'accessors',  implode("\n\n", $accessors));
        file_put_contents($this->speedyFile(), $contents);
    }

    /** @param list<string> $groups */
    private function rewriteFacadeAnnotations(array $groups): void
    {
        if (! file_exists($this->facadeFile())) {
            return;
        }
        $lines = ['/**'];
        $lines[] = ' * @method static \\Ux2Dev\\Speedy\\Speedy instance()';
        $lines[] = ' * @method static \\Ux2Dev\\Speedy\\Laravel\\SpeedyManager account(string $name)';
        foreach ($groups as $g) {
            $accessor = self::groupAccessor($g);
            $lines[] = " * @method static \\Ux2Dev\\Speedy\\Resources\\{$g} {$accessor}()";
        }
        $lines[] = ' */';
        $annotation = implode("\n", $lines);

        $contents = (string) file_get_contents($this->facadeFile());
        $contents = preg_replace('~/\*\*[\s\S]*?\*/\s*final class Speedy~', $annotation . "\nfinal class Speedy", $contents, 1);
        file_put_contents($this->facadeFile(), (string) $contents);
    }
}

// ---------------------------------------------------------------------------
// Naming helpers (namespaced functions)

function pascal(string $s): string
{
    $parts = preg_split('~[^A-Za-z0-9]+~', $s, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    return implode('', array_map(fn(string $p) => ucfirst($p), $parts));
}

function camel(string $s): string
{
    $p = pascal($s);
    return $p === '' ? $p : lcfirst($p);
}

function urnToSimpleName(string $urn): string
{
    $parts = explode(':', $urn);
    $tail  = end($parts);
    if ($tail === false || $tail === '') {
        throw new RuntimeException("Invalid schema URN: {$urn}");
    }
    return $tail;
}

function ensureDir(string $dir): void
{
    if (! is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

function rmdirRecursive(string $dir): void
{
    if (! is_dir($dir)) {
        return;
    }
    foreach (scandir($dir) ?: [] as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $path = $dir . '/' . $item;
        is_dir($path) ? rmdirRecursive($path) : unlink($path);
    }
    rmdir($dir);
}

function writeFile(string $path, string $contents): void
{
    ensureDir(dirname($path));
    file_put_contents($path, $contents);
}

/** Replace the block between `// <generated:NAME>` and `// </generated:NAME>`. */
function replaceMarkedBlock(string $haystack, string $marker, string $newContent): string
{
    $pattern = '~// <generated:' . preg_quote($marker, '~') . '>.*?// </generated:' . preg_quote($marker, '~') . '>~s';

    if (! preg_match($pattern, $haystack)) {
        throw new RuntimeException("Marker pair '{$marker}' not found in target file");
    }

    $replacement = "// <generated:{$marker}>\n{$newContent}\n    // </generated:{$marker}>";
    $out = preg_replace($pattern, $replacement, $haystack, 1);
    if ($out === null) {
        throw new RuntimeException("Replacement failed for marker '{$marker}'");
    }
    return $out;
}

// ---------------------------------------------------------------------------
// CLI entry — only runs when this file is invoked directly, not when required.

if (
    PHP_SAPI === 'cli'
    && isset($_SERVER['SCRIPT_FILENAME'])
    && realpath((string) $_SERVER['SCRIPT_FILENAME']) === __FILE__
) {
    $generator = new Generator(
        srcRoot:     __DIR__ . '/../src',
        schemaRoot:  __DIR__ . '/schemas',
        catalogPath: __DIR__ . '/endpoints.json',
    );
    $generator->run();
}

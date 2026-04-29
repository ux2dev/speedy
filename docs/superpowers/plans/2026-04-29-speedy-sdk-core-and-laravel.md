# Speedy SDK — Core + Generator + Laravel Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Ship the v0.1 of `ux2dev/speedy` — a framework-agnostic PHP SDK for the Speedy courier API at `https://api.speedy.bg/v1`, with a Laravel facade and multi-account manager. All resource classes and DTOs are emitted by a code generator that reads Speedy's published JSON schemas plus a hand-curated endpoints catalog.

**Architecture:** Tier 1 hand-written core (Config, Transport, exceptions, Resource base, value objects) plus a generator (`bin/generate.php`) that emits Resources and Request/Response/Model DTOs from `bin/schemas/` (snapshot of `https://api.speedy.bg/v1/schema`) and `bin/endpoints.json` (~50 hand-curated entries). Tier 2 adds a Laravel Service Provider, multi-account Manager, and Facade. All errors thrown as typed exceptions; success path always returns a "good" Response DTO. Models the same shape as `~/Herd/primio`.

**Tech Stack:** PHP ^8.2 with strict types, PSR-18 HTTP client, PSR-17 request/stream factories, Pest 4, Orchestra Testbench 10, Guzzle 7 (dev dep), Composer for packaging.

**Scope reference:** `docs/superpowers/specs/2026-04-29-speedy-sdk-design.md`. This plan implements Tiers 1 and 2 only — Tier 3 sub-modules (Nomenclatures, Shipments, Tracking) are deferred to subsequent plans.

---

## File structure created or modified by this plan

```
speedy/
├── .gitignore                                      Modify (add /bin/schemas/schema.zip etc.)
├── composer.json                                   Create
├── phpunit.xml                                     Create
├── README.md                                       Create
├── LICENSE                                         Create (MIT, copied from primio template)
├── bin/
│   ├── schemas/                                    Create (committed snapshot of /v1/schema)
│   ├── endpoints.json                              Create (hand-curated catalog)
│   ├── fetch-schemas.sh                            Create (helper, also runnable via composer script)
│   └── generate.php                                Create (emits DTOs + Resources)
├── src/
│   ├── Speedy.php                                  Create (root client; accessor block regenerated)
│   ├── Config/SpeedyConfig.php                     Create
│   ├── Http/
│   │   ├── SpeedyTransport.php                     Create
│   │   ├── PrintResult.php                         Create
│   │   └── ResultList.php                          Create
│   ├── Exception/
│   │   ├── SpeedyException.php                     Create
│   │   ├── ApiException.php                        Create
│   │   ├── ConfigurationException.php              Create
│   │   ├── TransportException.php                  Create
│   │   └── InvalidResponseException.php            Create
│   ├── Resources/
│   │   ├── Resource.php                            Create (hand-written base)
│   │   ├── Shipment.php                            Generated
│   │   ├── PrintService.php                        Generated
│   │   ├── Track.php                               Generated
│   │   ├── Pickup.php                              Generated
│   │   ├── Location.php                            Generated
│   │   ├── Calculate.php                           Generated
│   │   ├── Clients.php                             Generated
│   │   ├── Validation.php                          Generated
│   │   ├── Services.php                            Generated
│   │   └── Payments.php                            Generated
│   ├── Dto/
│   │   ├── Request/{Group}/*Request.php            Generated
│   │   ├── Response/{Group}/*Response.php          Generated
│   │   └── Model/*.php                             Generated
│   └── Laravel/
│       ├── SpeedyServiceProvider.php               Create
│       ├── SpeedyManager.php                       Create
│       ├── Facades/Speedy.php                      Create (annotations regenerated)
│       └── config/speedy.php                       Create
└── tests/
    ├── Pest.php                                    Create
    ├── TestCase.php                                Create
    ├── Http/
    │   ├── FakeHttpClient.php                      Create (test helper)
    │   ├── SpeedyTransportTest.php                 Create
    │   └── PrintResultTest.php                     Create
    ├── Config/SpeedyConfigTest.php                 Create
    ├── Resources/ResourcesIntegrationTest.php      Create
    ├── Bin/GeneratorSnapshotTest.php               Create
    ├── Dto/RoundtripTest.php                       Create
    └── Laravel/
        ├── TestCase.php                            Create
        ├── SpeedyManagerTest.php                   Create
        ├── FacadeTest.php                          Create
        └── PublishConfigTest.php                   Create
```

---

## Conventions used throughout this plan

- **Strict types**: every PHP file starts with `<?php\n\ndeclare(strict_types=1);\n`.
- **Final classes** unless inheritance is intentional (e.g., `Resource` base is abstract).
- **Final readonly** for value objects (`SpeedyConfig`, all Request DTOs, `PrintResult`).
- **No emojis** in any file or commit message.
- **Single-line commit messages**, no `Co-Authored-By` trailer (per user preference).
- **Pest tests** use the `it('...')` and `expect()->toBe()` styles; Laravel suite extends `Orchestra\Testbench\TestCase`.
- **Coverage gate**: 100% on `src/`. Generator (`bin/`) is tested via the snapshot test + the integration test that exercises every generated resource method.

---

## Task 1: Bootstrap package skeleton

**Files:**
- Create: `composer.json`, `phpunit.xml`, `README.md`, `LICENSE`, `tests/Pest.php`, `tests/TestCase.php`
- Modify: `.gitignore`

- [ ] **Step 1: Write `composer.json`**

```json
{
    "name": "ux2dev/speedy",
    "description": "Framework-agnostic PHP SDK for Speedy courier (api.speedy.bg)",
    "type": "library",
    "license": "MIT",
    "require": {
        "php": "^8.2",
        "ext-json": "*",
        "psr/http-client": "^1.0",
        "psr/http-factory": "^1.0"
    },
    "require-dev": {
        "pestphp/pest": "^4.0",
        "guzzlehttp/guzzle": "^7.0",
        "orchestra/testbench": "^10.0"
    },
    "suggest": {
        "guzzlehttp/guzzle": "Supplies PSR-18 client + PSR-17 factories out of the box",
        "illuminate/support": "Required for the Laravel facade and multi-account manager"
    },
    "autoload": {
        "psr-4": { "Ux2Dev\\Speedy\\": "src/" }
    },
    "autoload-dev": {
        "psr-4": { "Ux2Dev\\Speedy\\Tests\\": "tests/" }
    },
    "extra": {
        "laravel": {
            "providers": [
                "Ux2Dev\\Speedy\\Laravel\\SpeedyServiceProvider"
            ],
            "aliases": {
                "Speedy": "Ux2Dev\\Speedy\\Laravel\\Facades\\Speedy"
            }
        }
    },
    "minimum-stability": "stable",
    "config": {
        "allow-plugins": {
            "pestphp/pest-plugin": true
        }
    },
    "scripts": {
        "speedy:fetch-schemas": "bash bin/fetch-schemas.sh",
        "speedy:generate":      "php bin/generate.php"
    }
}
```

- [ ] **Step 2: Write `phpunit.xml`**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Speedy">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory>src</directory>
        </include>
    </source>
</phpunit>
```

- [ ] **Step 3: Write `tests/Pest.php`**

```php
<?php

declare(strict_types=1);

uses(Ux2Dev\Speedy\Tests\TestCase::class)->in(__DIR__);
```

- [ ] **Step 4: Write `tests/TestCase.php`**

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
}
```

- [ ] **Step 5: Update `.gitignore`** — append:

```
/bin/schemas/schema.zip
/.phpunit.cache/
```

- [ ] **Step 6: Write `LICENSE`** (MIT). Copy verbatim from `~/Herd/primio/LICENSE`.

- [ ] **Step 7: Write minimal `README.md`** (the full README lands in Task 21):

```markdown
# Speedy PHP SDK

> **Warning:** Developer testing version.

Framework-agnostic PHP SDK for the [Speedy](https://api.speedy.bg/api/docs/) courier API. Works with plain PHP or Laravel.

Full documentation lands in v0.1 — this README is a placeholder during initial implementation.
```

- [ ] **Step 8: Install dev dependencies**

Run: `composer install`
Expected: dependencies resolved, no errors.

- [ ] **Step 9: Commit**

```bash
git add composer.json phpunit.xml README.md LICENSE tests/Pest.php tests/TestCase.php .gitignore
git commit -m "Bootstrap package skeleton"
```

---

## Task 2: Exception hierarchy

**Files:**
- Create: `src/Exception/SpeedyException.php`
- Create: `src/Exception/ConfigurationException.php`
- Create: `src/Exception/TransportException.php`
- Create: `src/Exception/InvalidResponseException.php`
- Create: `src/Exception/ApiException.php`

- [ ] **Step 1: Write base exception**

`src/Exception/SpeedyException.php`:

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Exception;

use RuntimeException;

abstract class SpeedyException extends RuntimeException
{
}
```

- [ ] **Step 2: Write the four leaf exceptions**

`src/Exception/ConfigurationException.php`:

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Exception;

final class ConfigurationException extends SpeedyException
{
}
```

`src/Exception/TransportException.php`:

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Exception;

final class TransportException extends SpeedyException
{
}
```

`src/Exception/InvalidResponseException.php`:

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Exception;

final class InvalidResponseException extends SpeedyException
{
}
```

`src/Exception/ApiException.php`:

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Exception;

use Throwable;

final class ApiException extends SpeedyException
{
    /** @param array<string, mixed> $body */
    public function __construct(
        string $message,
        public readonly ?int $code = null,
        public readonly ?string $apiMessage = null,
        public readonly ?string $context = null,
        public readonly ?string $errorId = null,
        public readonly ?string $component = null,
        public readonly ?int $httpStatus = null,
        public readonly array $body = [],
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}
```

`$apiMessage` is the human-readable message from Speedy. The parent `$message` is the SDK-level wrapper string.

- [ ] **Step 3: Commit**

```bash
git add src/Exception/
git commit -m "Add exception hierarchy"
```

---

## Task 3: SpeedyConfig value object

**Files:**
- Create: `src/Config/SpeedyConfig.php`
- Create: `tests/Config/SpeedyConfigTest.php`

- [ ] **Step 1: Write the failing tests first**

`tests/Config/SpeedyConfigTest.php`:

```php
<?php

declare(strict_types=1);

use Ux2Dev\Speedy\Config\SpeedyConfig;
use Ux2Dev\Speedy\Exception\ConfigurationException;

it('accepts a fully populated config', function () {
    $config = new SpeedyConfig(
        baseUrl: 'https://api.speedy.bg/v1',
        userName: 'demo',
        password: 'secret',
        language: 'EN',
        clientSystemId: 42,
        timeout: 60,
    );

    expect($config->baseUrl)->toBe('https://api.speedy.bg/v1/');
    expect($config->userName)->toBe('demo');
    expect($config->getPassword())->toBe('secret');
    expect($config->language)->toBe('EN');
    expect($config->clientSystemId)->toBe(42);
    expect($config->timeout)->toBe(60);
});

it('uses sensible defaults', function () {
    $config = new SpeedyConfig(userName: 'demo', password: 'secret');

    expect($config->baseUrl)->toBe('https://api.speedy.bg/v1/');
    expect($config->language)->toBeNull();
    expect($config->clientSystemId)->toBeNull();
    expect($config->timeout)->toBe(30);
});

it('rejects empty userName', function () {
    new SpeedyConfig(userName: '', password: 'secret');
})->throws(ConfigurationException::class, 'userName must not be empty');

it('rejects empty password', function () {
    new SpeedyConfig(userName: 'demo', password: '');
})->throws(ConfigurationException::class, 'password must not be empty');

it('rejects malformed baseUrl', function () {
    new SpeedyConfig(baseUrl: 'ftp://nope', userName: 'demo', password: 'secret');
})->throws(ConfigurationException::class, 'baseUrl must start with http:// or https://');

it('rejects empty baseUrl', function () {
    new SpeedyConfig(baseUrl: '', userName: 'demo', password: 'secret');
})->throws(ConfigurationException::class, 'baseUrl must not be empty');

it('rejects timeout below 1', function () {
    new SpeedyConfig(userName: 'demo', password: 'secret', timeout: 0);
})->throws(ConfigurationException::class, 'timeout must be at least 1 second');

it('redacts password in __debugInfo', function () {
    $config = new SpeedyConfig(userName: 'demo', password: 'secret');

    $info = $config->__debugInfo();
    expect($info['password'])->toBe('[REDACTED]');
    expect($info['userName'])->toBe('demo');
});

it('blocks serialization', function () {
    $config = new SpeedyConfig(userName: 'demo', password: 'secret');

    expect(fn () => serialize($config))->toThrow(LogicException::class);
});

it('blocks unserialization', function () {
    $config = new SpeedyConfig(userName: 'demo', password: 'secret');

    expect(fn () => $config->__unserialize([]))->toThrow(LogicException::class);
});
```

- [ ] **Step 2: Run the tests to verify they fail**

Run: `vendor/bin/pest tests/Config/SpeedyConfigTest.php`
Expected: FAIL — `SpeedyConfig` class not found.

- [ ] **Step 3: Write the implementation**

`src/Config/SpeedyConfig.php`:

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Config;

use LogicException;
use Ux2Dev\Speedy\Exception\ConfigurationException;

final readonly class SpeedyConfig
{
    public string $baseUrl;

    public function __construct(
        string $baseUrl = 'https://api.speedy.bg/v1',
        public string $userName = '',
        private string $password = '',
        public ?string $language = null,
        public ?int $clientSystemId = null,
        public int $timeout = 30,
    ) {
        if ($baseUrl === '') {
            throw new ConfigurationException('baseUrl must not be empty');
        }
        if (! preg_match('~^https?://~i', $baseUrl)) {
            throw new ConfigurationException('baseUrl must start with http:// or https://');
        }
        if ($userName === '') {
            throw new ConfigurationException('userName must not be empty');
        }
        if ($password === '') {
            throw new ConfigurationException('password must not be empty');
        }
        if ($timeout < 1) {
            throw new ConfigurationException('timeout must be at least 1 second');
        }

        $this->baseUrl = rtrim($baseUrl, '/') . '/';
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /** @return array<string, mixed> */
    public function __debugInfo(): array
    {
        return [
            'baseUrl'        => $this->baseUrl,
            'userName'       => $this->userName,
            'password'       => $this->password !== '' ? '[REDACTED]' : '',
            'language'       => $this->language,
            'clientSystemId' => $this->clientSystemId,
            'timeout'        => $this->timeout,
        ];
    }

    /** @return array<int|string, mixed> */
    public function __serialize(): array
    {
        throw new LogicException('SpeedyConfig must not be serialized as it contains a password');
    }

    /** @param array<int|string, mixed> $data */
    public function __unserialize(array $data): void
    {
        throw new LogicException('SpeedyConfig must not be unserialized');
    }
}
```

- [ ] **Step 4: Run tests to verify they pass**

Run: `vendor/bin/pest tests/Config/SpeedyConfigTest.php`
Expected: PASS, 10 tests.

- [ ] **Step 5: Commit**

```bash
git add src/Config/SpeedyConfig.php tests/Config/SpeedyConfigTest.php
git commit -m "Add SpeedyConfig with validation, redaction and serialize-block"
```

---

## Task 4: Value objects (PrintResult, ResultList)

**Files:**
- Create: `src/Http/PrintResult.php`
- Create: `src/Http/ResultList.php`
- Create: `tests/Http/PrintResultTest.php`

- [ ] **Step 1: Write `tests/Http/PrintResultTest.php`**

```php
<?php

declare(strict_types=1);

use Ux2Dev\Speedy\Http\PrintResult;

it('exposes body, contentType and filename', function () {
    $r = new PrintResult('binary-bytes', 'application/pdf', 'voucher-1.pdf');

    expect($r->body)->toBe('binary-bytes');
    expect($r->contentType)->toBe('application/pdf');
    expect($r->filename)->toBe('voucher-1.pdf');
    expect($r->bytes())->toBe('binary-bytes');
});

it('detects PDF content', function () {
    $r = new PrintResult('%PDF-1.4...', 'application/pdf', 'a.pdf');

    expect($r->isPdf())->toBeTrue();
    expect($r->isZpl())->toBeFalse();
});

it('detects ZPL content from text/plain', function () {
    $r = new PrintResult('^XA^FO0,0...', 'text/plain', null);

    expect($r->isZpl())->toBeTrue();
    expect($r->isPdf())->toBeFalse();
});

it('detects ZPL content from application/zpl', function () {
    $r = new PrintResult('^XA^FO0,0...', 'application/zpl', null);

    expect($r->isZpl())->toBeTrue();
});

it('writes bytes to disk via saveTo', function () {
    $path = tempnam(sys_get_temp_dir(), 'speedy-test-');
    $r = new PrintResult('hello bytes', 'application/pdf', 'a.pdf');

    $written = $r->saveTo($path);

    expect($written)->toBe(11);
    expect(file_get_contents($path))->toBe('hello bytes');
    unlink($path);
});
```

- [ ] **Step 2: Run tests to verify they fail**

Run: `vendor/bin/pest tests/Http/PrintResultTest.php`
Expected: FAIL — class not found.

- [ ] **Step 3: Write `src/Http/PrintResult.php`**

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Http;

use RuntimeException;

final class PrintResult
{
    public function __construct(
        public readonly string $body,
        public readonly string $contentType,
        public readonly ?string $filename = null,
    ) {
    }

    public function bytes(): string
    {
        return $this->body;
    }

    public function saveTo(string $path): int
    {
        $written = file_put_contents($path, $this->body);

        if ($written === false) {
            throw new RuntimeException("Failed to write PrintResult to {$path}");
        }

        return $written;
    }

    public function isPdf(): bool
    {
        return str_starts_with($this->contentType, 'application/pdf');
    }

    public function isZpl(): bool
    {
        return $this->contentType === 'text/plain'
            || str_starts_with($this->contentType, 'application/zpl')
            || str_starts_with($this->contentType, 'application/x-zpl');
    }
}
```

- [ ] **Step 4: Write `src/Http/ResultList.php`**

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Http;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * @template T
 * @implements IteratorAggregate<int, T>
 */
final class ResultList implements IteratorAggregate, Countable
{
    /** @param list<T> $items */
    public function __construct(
        public readonly array $items,
    ) {
    }

    /** @return list<T> */
    public function all(): array
    {
        return $this->items;
    }

    /** @return T|null */
    public function first(): mixed
    {
        return $this->items[0] ?? null;
    }

    public function count(): int
    {
        return count($this->items);
    }

    /** @return ArrayIterator<int, T> */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }
}
```

- [ ] **Step 5: Run tests to verify pass**

Run: `vendor/bin/pest tests/Http/PrintResultTest.php`
Expected: PASS, 5 tests.

- [ ] **Step 6: Commit**

```bash
git add src/Http/PrintResult.php src/Http/ResultList.php tests/Http/PrintResultTest.php
git commit -m "Add PrintResult and ResultList value objects"
```

---

## Task 5: Resource base + Speedy root skeleton

**Files:**
- Create: `src/Resources/Resource.php`
- Create: `src/Speedy.php`

- [ ] **Step 1: Write `src/Resources/Resource.php`**

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Resources;

use Ux2Dev\Speedy\Http\SpeedyTransport;

abstract class Resource
{
    public function __construct(protected readonly SpeedyTransport $transport)
    {
    }
}
```

- [ ] **Step 2: Write the skeleton `src/Speedy.php`**

The accessor block between the markers will be regenerated by `bin/generate.php`. Keep the markers stable.

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Ux2Dev\Speedy\Config\SpeedyConfig;
use Ux2Dev\Speedy\Http\SpeedyTransport;

/**
 * Framework-agnostic entry point for the Speedy SDK. Instantiate once per
 * account with a PSR-18 client + PSR-17 factories, then dispatch requests
 * via the resource accessors ($speedy->shipment(), etc.).
 */
final class Speedy
{
    public readonly SpeedyTransport $transport;

    // <generated:properties>
    // </generated:properties>

    public function __construct(
        SpeedyConfig $config,
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
    ) {
        $this->transport = new SpeedyTransport($config, $httpClient, $requestFactory, $streamFactory);
    }

    // <generated:accessors>
    // </generated:accessors>
}
```

- [ ] **Step 3: Commit**

```bash
git add src/Resources/Resource.php src/Speedy.php
git commit -m "Add Resource base and Speedy root skeleton"
```

---

## Task 6: SpeedyTransport

**Files:**
- Create: `src/Http/SpeedyTransport.php`
- Create: `tests/Http/FakeHttpClient.php`
- Create: `tests/Http/SpeedyTransportTest.php`

- [ ] **Step 1: Write `tests/Http/FakeHttpClient.php`** (test helper)

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Tests\Http;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Programmable PSR-18 client. Records every outgoing request and returns
 * canned responses in FIFO order. Used in transport, integration and
 * Laravel tests.
 */
final class FakeHttpClient implements ClientInterface
{
    /** @var list<RequestInterface> */
    public array $requests = [];

    /** @var list<ResponseInterface> */
    private array $responses = [];

    /** @var array<int, \Throwable> */
    private array $exceptions = [];

    /** @param array<string, string> $headers */
    public function queueJson(int $status, array $body, array $headers = []): void
    {
        $this->responses[] = new Response(
            $status,
            $headers + ['Content-Type' => 'application/json'],
            json_encode($body, JSON_THROW_ON_ERROR),
        );
    }

    /** @param array<string, string> $headers */
    public function queueBinary(int $status, string $body, string $contentType, array $headers = []): void
    {
        $this->responses[] = new Response(
            $status,
            $headers + ['Content-Type' => $contentType],
            $body,
        );
    }

    public function queueRaw(ResponseInterface $response): void
    {
        $this->responses[] = $response;
    }

    public function queueException(\Throwable $e): void
    {
        $this->exceptions[count($this->responses)] = $e;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $idx = count($this->requests);
        $this->requests[] = $request;

        if (isset($this->exceptions[$idx])) {
            throw $this->exceptions[$idx];
        }

        if (! isset($this->responses[$idx])) {
            throw new \LogicException('FakeHttpClient ran out of queued responses');
        }

        return $this->responses[$idx];
    }

    /** @return array<string, mixed> */
    public function lastRequestBody(): array
    {
        $last = end($this->requests);
        if ($last === false) {
            throw new \LogicException('No requests recorded');
        }
        $body = (string) $last->getBody();
        $decoded = json_decode($body, true, flags: JSON_THROW_ON_ERROR);
        if (! is_array($decoded)) {
            throw new \LogicException('Last request body was not a JSON object');
        }
        return $decoded;
    }
}
```

- [ ] **Step 2: Write `tests/Http/SpeedyTransportTest.php`**

```php
<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use Ux2Dev\Speedy\Config\SpeedyConfig;
use Ux2Dev\Speedy\Exception\ApiException;
use Ux2Dev\Speedy\Exception\InvalidResponseException;
use Ux2Dev\Speedy\Exception\TransportException;
use Ux2Dev\Speedy\Http\PrintResult;
use Ux2Dev\Speedy\Http\SpeedyTransport;
use Ux2Dev\Speedy\Tests\Http\FakeHttpClient;

final class FakeResponse
{
    public function __construct(public array $data = []) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}

function makeTransport(FakeHttpClient $client, ?string $defaultLanguage = 'EN', ?int $defaultClientSystemId = null): SpeedyTransport
{
    $config = new SpeedyConfig(
        userName: 'demo',
        password: 'secret',
        language: $defaultLanguage,
        clientSystemId: $defaultClientSystemId,
    );
    $factory = new HttpFactory();
    return new SpeedyTransport($config, $client, $factory, $factory);
}

it('postJson auto-injects credentials and language', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, ['hello' => 'world']);

    makeTransport($client)->postJson('/shipment', ['ref1' => 'ABC'], FakeResponse::class);

    $body = $client->lastRequestBody();
    expect($body)->toMatchArray([
        'userName' => 'demo',
        'password' => 'secret',
        'language' => 'EN',
        'ref1'     => 'ABC',
    ]);
    expect((string) $client->requests[0]->getUri())->toBe('https://api.speedy.bg/v1/shipment');
    expect($client->requests[0]->getMethod())->toBe('POST');
});

it('per-call body fields override config defaults', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, []);

    makeTransport($client, defaultLanguage: 'EN')
        ->postJson('/shipment', ['language' => 'BG'], FakeResponse::class);

    expect($client->lastRequestBody()['language'])->toBe('BG');
});

it('does not inject clientSystemId when not configured and not in body', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, []);

    makeTransport($client, defaultClientSystemId: null)
        ->postJson('/shipment', [], FakeResponse::class);

    expect($client->lastRequestBody())->not->toHaveKey('clientSystemId');
});

it('returns the typed response DTO via fromArray', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, ['ok' => true]);

    $result = makeTransport($client)->postJson('/x', [], FakeResponse::class);

    expect($result)->toBeInstanceOf(FakeResponse::class);
    expect($result->data)->toBe(['ok' => true]);
});

it('throws ApiException when response carries a non-null error', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, [
        'error' => ['code' => 1234, 'message' => 'No no', 'context' => 'shipment', 'id' => 'X', 'component' => 'svc'],
    ]);

    expect(fn () => makeTransport($client)->postJson('/x', [], FakeResponse::class))
        ->toThrow(ApiException::class);
});

it('exposes structured error fields on ApiException', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, [
        'error' => ['code' => 1234, 'message' => 'No no', 'context' => 'ctx', 'id' => 'ID', 'component' => 'cmp'],
    ]);

    try {
        makeTransport($client)->postJson('/x', [], FakeResponse::class);
        $this->fail('ApiException not thrown');
    } catch (ApiException $e) {
        expect($e->code)->toBe(1234);
        expect($e->apiMessage)->toBe('No no');
        expect($e->context)->toBe('ctx');
        expect($e->errorId)->toBe('ID');
        expect($e->component)->toBe('cmp');
        expect($e->httpStatus)->toBe(200);
    }
});

it('throws TransportException on PSR-18 client failure', function () {
    $client = new FakeHttpClient();
    $client->queueException(new class extends \Exception implements \Psr\Http\Client\ClientExceptionInterface {});

    expect(fn () => makeTransport($client)->postJson('/x', [], FakeResponse::class))
        ->toThrow(TransportException::class);
});

it('throws InvalidResponseException on empty body', function () {
    $client = new FakeHttpClient();
    $client->queueRaw(new Response(200, [], ''));

    expect(fn () => makeTransport($client)->postJson('/x', [], FakeResponse::class))
        ->toThrow(InvalidResponseException::class);
});

it('throws InvalidResponseException on malformed JSON', function () {
    $client = new FakeHttpClient();
    $client->queueRaw(new Response(200, [], '{not json'));

    expect(fn () => makeTransport($client)->postJson('/x', [], FakeResponse::class))
        ->toThrow(InvalidResponseException::class);
});

it('postBinary returns PrintResult with body and Content-Type', function () {
    $client = new FakeHttpClient();
    $client->queueBinary(200, '%PDF-1.4 fake', 'application/pdf', [
        'Content-Disposition' => 'attachment; filename="voucher.pdf"',
    ]);

    $result = makeTransport($client)->postBinary('/print', ['barcodes' => ['X']]);

    expect($result)->toBeInstanceOf(PrintResult::class);
    expect($result->body)->toBe('%PDF-1.4 fake');
    expect($result->contentType)->toBe('application/pdf');
    expect($result->filename)->toBe('voucher.pdf');
});

it('postBinary leaves filename null when no Content-Disposition', function () {
    $client = new FakeHttpClient();
    $client->queueBinary(200, '%PDF-1.4 fake', 'application/pdf');

    $result = makeTransport($client)->postBinary('/print', []);

    expect($result->filename)->toBeNull();
});

it('getJson sends GET method', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, []);

    makeTransport($client)->getJson('/x', [], FakeResponse::class);

    expect($client->requests[0]->getMethod())->toBe('GET');
});

it('deleteJson sends DELETE method', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, []);

    makeTransport($client)->deleteJson('/x', [], FakeResponse::class);

    expect($client->requests[0]->getMethod())->toBe('DELETE');
});
```

- [ ] **Step 3: Run tests to verify they fail**

Run: `vendor/bin/pest tests/Http/SpeedyTransportTest.php`
Expected: FAIL — `SpeedyTransport` class not found.

- [ ] **Step 4: Write `src/Http/SpeedyTransport.php`**

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Http;

use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Ux2Dev\Speedy\Config\SpeedyConfig;
use Ux2Dev\Speedy\Exception\ApiException;
use Ux2Dev\Speedy\Exception\InvalidResponseException;
use Ux2Dev\Speedy\Exception\TransportException;

final class SpeedyTransport
{
    public function __construct(
        public readonly SpeedyConfig $config,
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
    ) {
    }

    /**
     * @template T of object
     * @param  array<string, mixed> $body
     * @param  class-string<T>      $responseClass  must expose static fromArray(array): self
     * @return T
     */
    public function postJson(string $path, array $body, string $responseClass): object
    {
        return $this->sendJson('POST', $path, $body, $responseClass);
    }

    /**
     * @template T of object
     * @param  array<string, mixed> $body
     * @param  class-string<T>      $responseClass
     * @return T
     */
    public function getJson(string $path, array $body, string $responseClass): object
    {
        return $this->sendJson('GET', $path, $body, $responseClass);
    }

    /**
     * @template T of object
     * @param  array<string, mixed> $body
     * @param  class-string<T>      $responseClass
     * @return T
     */
    public function deleteJson(string $path, array $body, string $responseClass): object
    {
        return $this->sendJson('DELETE', $path, $body, $responseClass);
    }

    /** @param array<string, mixed> $body */
    public function postBinary(string $path, array $body): PrintResult
    {
        $response = $this->dispatch('POST', $path, $body);
        $status   = $response->getStatusCode();
        $raw      = (string) $response->getBody();

        if ($raw === '') {
            throw new InvalidResponseException("Empty binary response body (HTTP {$status})");
        }

        $contentType = $response->getHeaderLine('Content-Type') ?: 'application/octet-stream';
        $disposition = $response->getHeaderLine('Content-Disposition');
        $filename    = null;
        if ($disposition !== '' && preg_match('~filename="?([^";]+)"?~', $disposition, $m)) {
            $filename = $m[1];
        }

        return new PrintResult($raw, $contentType, $filename);
    }

    /**
     * @template T of object
     * @param  array<string, mixed> $body
     * @param  class-string<T>      $responseClass
     * @return T
     */
    private function sendJson(string $method, string $path, array $body, string $responseClass): object
    {
        $response = $this->dispatch($method, $path, $body);
        $status   = $response->getStatusCode();
        $raw      = (string) $response->getBody();

        if ($raw === '') {
            throw new InvalidResponseException("Empty response body (HTTP {$status})");
        }

        try {
            $decoded = json_decode($raw, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new InvalidResponseException(
                "Malformed JSON response (HTTP {$status}): " . $e->getMessage(),
                previous: $e,
            );
        }

        if (! is_array($decoded)) {
            throw new InvalidResponseException('Expected JSON object, got ' . gettype($decoded));
        }

        if (isset($decoded['error']) && is_array($decoded['error']) && $decoded['error'] !== []) {
            $err = $decoded['error'];
            throw new ApiException(
                'Speedy API error: ' . ($err['message'] ?? 'unknown'),
                code: isset($err['code']) ? (int) $err['code'] : null,
                apiMessage: isset($err['message']) ? (string) $err['message'] : null,
                context: isset($err['context']) ? (string) $err['context'] : null,
                errorId: isset($err['id']) ? (string) $err['id'] : null,
                component: isset($err['component']) ? (string) $err['component'] : null,
                httpStatus: $status,
                body: $decoded,
            );
        }

        return $responseClass::fromArray($decoded);
    }

    /** @param array<string, mixed> $body */
    private function dispatch(string $method, string $path, array $body): ResponseInterface
    {
        $merged = $this->mergeAuth($body);

        try {
            $json = json_encode($merged, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (JsonException $e) {
            throw new InvalidResponseException('Failed to encode request body: ' . $e->getMessage(), previous: $e);
        }

        $url = $this->config->baseUrl . ltrim($path, '/');

        $request = $this->requestFactory->createRequest($method, $url)
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withHeader('Accept', 'application/json')
            ->withBody($this->streamFactory->createStream($json));

        try {
            return $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new TransportException('HTTP transport error: ' . $e->getMessage(), previous: $e);
        }
    }

    /**
     * @param  array<string, mixed> $body
     * @return array<string, mixed>
     */
    private function mergeAuth(array $body): array
    {
        $body['userName'] = $body['userName'] ?? $this->config->userName;
        $body['password'] = $body['password'] ?? $this->config->getPassword();

        if (! array_key_exists('language', $body) && $this->config->language !== null) {
            $body['language'] = $this->config->language;
        }
        if (! array_key_exists('clientSystemId', $body) && $this->config->clientSystemId !== null) {
            $body['clientSystemId'] = $this->config->clientSystemId;
        }

        return $body;
    }
}
```

- [ ] **Step 5: Run tests to verify pass**

Run: `vendor/bin/pest tests/Http/SpeedyTransportTest.php`
Expected: PASS, 13 tests.

- [ ] **Step 6: Commit**

```bash
git add src/Http/SpeedyTransport.php tests/Http/FakeHttpClient.php tests/Http/SpeedyTransportTest.php
git commit -m "Add SpeedyTransport with credential auto-injection and error mapping"
```

---

## Task 7: Snapshot Speedy schemas to `bin/schemas/`

**Files:**
- Create: `bin/fetch-schemas.sh`
- Create: `bin/schemas/*.schema.json` (~180 files, downloaded then committed)

- [ ] **Step 1: Write `bin/fetch-schemas.sh`**

```bash
#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

mkdir -p bin/schemas
rm -f bin/schemas/*.schema.json bin/schemas/schema.zip

curl -sSL https://api.speedy.bg/v1/schema -o bin/schemas/schema.zip
unzip -q -o bin/schemas/schema.zip -d bin/schemas
rm bin/schemas/schema.zip

echo "Snapshotted $(ls bin/schemas/*.schema.json | wc -l | tr -d ' ') schemas to bin/schemas/"
```

- [ ] **Step 2: Make it executable and run it**

Run:
```bash
chmod +x bin/fetch-schemas.sh
composer speedy:fetch-schemas
```
Expected: "Snapshotted ~181 schemas to bin/schemas/"

- [ ] **Step 3: Verify schemas committed properly**

Run: `ls bin/schemas/ | head` then `ls bin/schemas/*.schema.json | wc -l`
Expected: ~181 .schema.json files.

- [ ] **Step 4: Commit**

```bash
git add bin/fetch-schemas.sh bin/schemas/
git commit -m "Snapshot Speedy v1 schema bundle to bin/schemas"
```

---

## Task 8: Seed `bin/endpoints.json`

The seed catalog covers ten representative operations spanning every method/return shape combination, so that when Task 13 lands, the integration test exercises every code path of the generator. Task 16 expands this to the full catalog.

**Files:**
- Create: `bin/endpoints.json`

- [ ] **Step 1: Write the seed catalog**

`bin/endpoints.json`:

```json
[
  {
    "group":    "Shipment",
    "name":     "create",
    "method":   "POST",
    "path":     "/shipment",
    "request":  "CreateShipmentRequest",
    "response": "CreateShipmentResponse",
    "returns":  "json"
  },
  {
    "group":    "Shipment",
    "name":     "cancel",
    "method":   "POST",
    "path":     "/shipment/cancel",
    "request":  "CancelShipmentRequest",
    "response": "CancelShipmentResponse",
    "returns":  "json"
  },
  {
    "group":    "Shipment",
    "name":     "info",
    "method":   "POST",
    "path":     "/shipment/info",
    "request":  "ShipmentInformationRequest",
    "response": "ShipmentInformationResponse",
    "returns":  "json"
  },
  {
    "group":    "Track",
    "name":     "track",
    "method":   "POST",
    "path":     "/track",
    "request":  "TrackRequest",
    "response": "TrackResponse",
    "returns":  "json"
  },
  {
    "group":    "Location",
    "name":     "findCountry",
    "method":   "POST",
    "path":     "/location/country",
    "request":  "FindCountryRequest",
    "response": "FindCountryResponse",
    "returns":  "json"
  },
  {
    "group":    "Location",
    "name":     "findOffice",
    "method":   "POST",
    "path":     "/location/office",
    "request":  "FindOfficeRequest",
    "response": "FindOfficeResponse",
    "returns":  "json"
  },
  {
    "group":    "Calculate",
    "name":     "calculate",
    "method":   "POST",
    "path":     "/calculate",
    "request":  "CalculationRequest",
    "response": "CalculationResponse",
    "returns":  "json"
  },
  {
    "group":    "Validation",
    "name":     "validateAddress",
    "method":   "POST",
    "path":     "/validation/address",
    "request":  "ValidateAddressRequest",
    "response": "ValidationResponse",
    "returns":  "json"
  },
  {
    "group":    "PrintService",
    "name":     "voucher",
    "method":   "POST",
    "path":     "/print",
    "request":  "PrintVoucherRequest",
    "response": null,
    "returns":  "bytes"
  },
  {
    "group":    "Services",
    "name":     "destinationServices",
    "method":   "POST",
    "path":     "/services/destination",
    "request":  "DestinationServicesRequest",
    "response": "DestinationServicesResponse",
    "returns":  "json"
  }
]
```

The `group` `"PrintService"` maps to `Resources\PrintService` (Speedy doc calls it the Print Service; the class name `Print` is reserved and can't be used).

- [ ] **Step 2: Commit**

```bash
git add bin/endpoints.json
git commit -m "Seed bin/endpoints.json with ten representative operations"
```

---

## Task 9: Generator — schema loader and helper functions

The generator is structured so that all paths come from `define()`-able constants, allowing the snapshot test (Task 15) to point it at a temporary directory by defining the constants before `require_once`. The generator only invokes `main()` when run as a CLI entry point.

**Files:**
- Create: `bin/generate.php` (skeleton + helpers — emission stages added in Tasks 10-13)

- [ ] **Step 1: Scaffold `bin/generate.php`**

```php
<?php

declare(strict_types=1);

/**
 * Speedy SDK code generator.
 *
 * Reads:
 *   - SPEEDY_SCHEMA_ROOT/*.schema.json   (snapshot of https://api.speedy.bg/v1/schema)
 *   - SPEEDY_CATALOG_PATH                (hand-curated operation catalog)
 *
 * Emits:
 *   - SPEEDY_SRC_ROOT/Dto/Model/*.php
 *   - SPEEDY_SRC_ROOT/Dto/Request/{Group}/*Request.php
 *   - SPEEDY_SRC_ROOT/Dto/Response/{Group}/*Response.php
 *   - SPEEDY_SRC_ROOT/Resources/{Group}.php
 *   - SPEEDY_SRC_ROOT/Speedy.php (resource accessors only — markers preserved)
 *   - SPEEDY_SRC_ROOT/Laravel/Facades/Speedy.php (@method annotations only)
 *
 * Constants are defined conditionally so the snapshot test can override them
 * via `define()` before `require_once`-ing this file.
 */

if (! defined('SPEEDY_SRC_ROOT')) {
    define('SPEEDY_SRC_ROOT', __DIR__ . '/../src');
}
if (! defined('SPEEDY_SCHEMA_ROOT')) {
    define('SPEEDY_SCHEMA_ROOT', __DIR__ . '/schemas');
}
if (! defined('SPEEDY_CATALOG_PATH')) {
    define('SPEEDY_CATALOG_PATH', __DIR__ . '/endpoints.json');
}

const NS_REQUEST   = 'Ux2Dev\\Speedy\\Dto\\Request';
const NS_RESPONSE  = 'Ux2Dev\\Speedy\\Dto\\Response';
const NS_MODEL     = 'Ux2Dev\\Speedy\\Dto\\Model';

/** Auth fields auto-injected by SpeedyTransport — stripped from generated request DTOs. */
const STRIPPED_REQUEST_FIELDS = ['userName', 'password', 'language', 'clientSystemId'];

function speedy_request_root(): string  { return SPEEDY_SRC_ROOT . '/Dto/Request'; }
function speedy_response_root(): string { return SPEEDY_SRC_ROOT . '/Dto/Response'; }
function speedy_model_root(): string    { return SPEEDY_SRC_ROOT . '/Dto/Model'; }
function speedy_resources_root(): string { return SPEEDY_SRC_ROOT . '/Resources'; }
function speedy_speedy_file(): string    { return SPEEDY_SRC_ROOT . '/Speedy.php'; }
function speedy_facade_file(): string    { return SPEEDY_SRC_ROOT . '/Laravel/Facades/Speedy.php'; }

// ---------------------------------------------------------------------------
// Schema loading

/** @return array<string, array<string, mixed>> */
function loadSchemas(): array
{
    $out = [];
    foreach (glob(SPEEDY_SCHEMA_ROOT . '/*.schema.json') ?: [] as $path) {
        $name = basename($path, '.schema.json');
        $data = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        if (! is_array($data)) {
            throw new RuntimeException("Schema {$path} did not parse to an object");
        }
        $out[$name] = $data;
    }
    return $out;
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

// ---------------------------------------------------------------------------
// Naming helpers

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

// ---------------------------------------------------------------------------
// JSON-Schema → PHP type mapping

/**
 * @param array<string, mixed> $prop
 * @param array<string, true>  $modelsOut accumulator (by reference)
 * @return array{0: string, 1: string, 2: string}  [phpType, fromArrayExpr, toArrayStmt]
 */
function mapProperty(string $key, array $prop, array &$modelsOut): array
{
    $keyLit = var_export($key, true);
    $php    = camel($key);

    if (isset($prop['$ref']) && is_string($prop['$ref'])) {
        $model = urnToSimpleName($prop['$ref']);
        $modelsOut[$model] = true;
        $type = '?\\' . NS_MODEL . '\\' . $model;
        $from = "isset(\$data[{$keyLit}]) && is_array(\$data[{$keyLit}]) ? \\" . NS_MODEL . "\\{$model}::fromArray(\$data[{$keyLit}]) : null";
        $to   = "if (\$this->{$php} !== null) \$out[{$keyLit}] = \$this->{$php}->toArray();";
        return [$type, $from, $to];
    }

    $type = $prop['type'] ?? 'string';

    if ($type === 'array') {
        $items = $prop['items'] ?? [];
        if (is_array($items) && isset($items['$ref']) && is_string($items['$ref'])) {
            $model = urnToSimpleName($items['$ref']);
            $modelsOut[$model] = true;
            $php_  = $php;
            $type_ = '?array';
            $from  = "isset(\$data[{$keyLit}]) && is_array(\$data[{$keyLit}]) ? array_map(fn(array \$r) => \\" . NS_MODEL . "\\{$model}::fromArray(\$r), \$data[{$keyLit}]) : null";
            $to    = "if (\$this->{$php_} !== null) \$out[{$keyLit}] = array_map(fn(\\" . NS_MODEL . "\\{$model} \$x) => \$x->toArray(), \$this->{$php_});";
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

// ---------------------------------------------------------------------------
// Filesystem helpers

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
    $replacement = "// <generated:{$marker}>\n{$newContent}\n    // </generated:{$marker}>";
    $out = preg_replace($pattern, $replacement, $haystack, 1);
    if ($out === null || $out === $haystack) {
        throw new RuntimeException("Marker pair '{$marker}' not found in target file");
    }
    return $out;
}
```

- [ ] **Step 2: Verify it parses**

Run: `php -l bin/generate.php`
Expected: "No syntax errors detected".

- [ ] **Step 3: Commit**

```bash
git add bin/generate.php
git commit -m "Add generator skeleton with schema loader and helpers"
```

---

## Task 10: Generator — Model DTO emission

**Files:** Modify `bin/generate.php`.

- [ ] **Step 1: Append Model DTO renderer**

```php
// ---------------------------------------------------------------------------
// Model DTO emission

/**
 * @param array<string, mixed> $schema
 * @return array{code: string, refs: array<string, true>}
 */
function renderModelDto(string $name, array $schema): array
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
        [$type, $from, $to] = mapProperty($key, $prop, $refs);
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
 * Emit every model schema reachable from $seedNames via $ref traversal.
 *
 * @param array<string, true> $seedNames
 * @param array<string, array<string, mixed>> $allSchemas
 */
function emitModels(array $seedNames, array $allSchemas): void
{
    rmdirRecursive(speedy_model_root());
    ensureDir(speedy_model_root());

    $queue   = $seedNames;
    $emitted = [];

    while ($queue !== []) {
        $name = array_key_first($queue);
        unset($queue[$name]);

        if (isset($emitted[$name])) {
            continue;
        }
        if (! isset($allSchemas[$name])) {
            // Some Request/Response names show up here — skip them; they belong
            // under Dto/Request or Dto/Response, not Dto/Model.
            continue;
        }

        $rendered = renderModelDto($name, $allSchemas[$name]);
        writeFile(speedy_model_root() . '/' . $name . '.php', $rendered['code']);
        $emitted[$name] = true;

        foreach ($rendered['refs'] as $ref => $_) {
            if (! isset($emitted[$ref])) {
                $queue[$ref] = true;
            }
        }
    }
}
```

- [ ] **Step 2: Syntax check**

Run: `php -l bin/generate.php`
Expected: "No syntax errors detected".

- [ ] **Step 3: Commit**

```bash
git add bin/generate.php
git commit -m "Add Model DTO emission to generator"
```

---

## Task 11: Generator — Request DTO emission

**Files:** Modify `bin/generate.php`.

- [ ] **Step 1: Append Request DTO renderer**

```php
// ---------------------------------------------------------------------------
// Request DTO emission

/**
 * @param array<string, mixed> $schema
 * @param array<string, true>  $modelsOut
 */
function emitRequestDto(string $group, string $className, array $schema, array &$modelsOut): void
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
        [$type, $_from, $to] = mapProperty($key, $prop, $modelsOut);
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

    $namespace = NS_REQUEST . '\\' . $group;

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

    writeFile(speedy_request_root() . '/' . $group . '/' . $className . '.php', $code);
}
```

- [ ] **Step 2: Syntax check**

Run: `php -l bin/generate.php`
Expected: "No syntax errors detected".

- [ ] **Step 3: Commit**

```bash
git add bin/generate.php
git commit -m "Add Request DTO emission to generator"
```

---

## Task 12: Generator — Response DTO emission

**Files:** Modify `bin/generate.php`.

- [ ] **Step 1: Append Response DTO renderer**

```php
// ---------------------------------------------------------------------------
// Response DTO emission

/**
 * @param array<string, mixed> $schema
 * @param array<string, true>  $modelsOut
 */
function emitResponseDto(string $group, string $className, array $schema, array &$modelsOut): void
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
        [$type, $from, $_to] = mapProperty($key, $prop, $modelsOut);
        $php          = camel($key);
        $ctor[]       = "        public readonly {$type} \${$php} = null,";
        $fromArray[]  = "            {$php}: {$from},";
    }

    if ($ctor === []) {
        $code = renderGenericResponseDto($group, $className);
        writeFile(speedy_response_root() . '/' . $group . '/' . $className . '.php', $code);
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

    writeFile(speedy_response_root() . '/' . $group . '/' . $className . '.php', $code);
}

function renderGenericResponseDto(string $group, string $className): string
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
```

- [ ] **Step 2: Syntax check**

Run: `php -l bin/generate.php`
Expected: "No syntax errors detected".

- [ ] **Step 3: Commit**

```bash
git add bin/generate.php
git commit -m "Add Response DTO emission to generator"
```

---

## Task 13: Generator — Resource emission, Speedy root rewrite, conditional main

**Files:** Modify `bin/generate.php`.

- [ ] **Step 1: Append Resource renderer + main entry point**

```php
// ---------------------------------------------------------------------------
// Resource emission

/** @param list<array<string, mixed>> $methods */
function renderResource(string $group, array $methods): string
{
    $uses = [
        'Ux2Dev\\Speedy\\Resources\\Resource' => true,
    ];
    $methodCode = [];

    foreach ($methods as $m) {
        $name     = $m['name'];
        $path     = $m['path'];
        $method   = strtoupper($m['method']);
        $reqClass = $m['request'];
        $reqFqn   = NS_REQUEST . '\\' . $group . '\\' . $reqClass;
        $uses[$reqFqn] = true;

        if ($m['returns'] === 'bytes') {
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
function groupAccessor(string $group): string
{
    return $group === 'PrintService' ? 'print' : lcfirst($group);
}

/** @param list<string> $groups */
function rewriteSpeedyRoot(array $groups): void
{
    $properties = [];
    $accessors  = [];
    foreach ($groups as $g) {
        $accessor = groupAccessor($g);
        $properties[] = "private ?\\Ux2Dev\\Speedy\\Resources\\{$g} \${$accessor} = null;";
        $accessors[]  = <<<PHP
    public function {$accessor}(): \\Ux2Dev\\Speedy\\Resources\\{$g}
    {
        return \$this->{$accessor} ??= new \\Ux2Dev\\Speedy\\Resources\\{$g}(\$this->transport);
    }
PHP;
    }

    $contents = (string) file_get_contents(speedy_speedy_file());
    $propsBlock = '    ' . implode("\n    ", $properties);
    $contents = replaceMarkedBlock($contents, 'properties', $propsBlock);
    $contents = replaceMarkedBlock($contents, 'accessors',  implode("\n\n", $accessors));
    file_put_contents(speedy_speedy_file(), $contents);
}

/** @param list<string> $groups */
function rewriteFacadeAnnotations(array $groups): void
{
    if (! file_exists(speedy_facade_file())) {
        return;
    }
    $lines = ['/**'];
    $lines[] = ' * @method static \\Ux2Dev\\Speedy\\Speedy instance()';
    $lines[] = ' * @method static \\Ux2Dev\\Speedy\\Laravel\\SpeedyManager account(string $name)';
    foreach ($groups as $g) {
        $accessor = groupAccessor($g);
        $lines[] = " * @method static \\Ux2Dev\\Speedy\\Resources\\{$g} {$accessor}()";
    }
    $lines[] = ' */';
    $annotation = implode("\n", $lines);

    $contents = (string) file_get_contents(speedy_facade_file());
    $contents = preg_replace('~/\*\*[\s\S]*?\*/\s*final class Speedy~', $annotation . "\nfinal class Speedy", $contents, 1);
    file_put_contents(speedy_facade_file(), (string) $contents);
}

// ---------------------------------------------------------------------------
// Main

function speedy_generate_main(): void
{
    if (! file_exists(SPEEDY_CATALOG_PATH)) {
        fwrite(STDERR, "endpoints catalog not found at " . SPEEDY_CATALOG_PATH . "\n");
        exit(1);
    }
    $catalog = json_decode((string) file_get_contents(SPEEDY_CATALOG_PATH), true, flags: JSON_THROW_ON_ERROR);
    if (! is_array($catalog)) {
        fwrite(STDERR, "endpoints.json must be a JSON array\n");
        exit(1);
    }

    $allSchemas = loadSchemas();

    rmdirRecursive(speedy_request_root());
    rmdirRecursive(speedy_response_root());
    rmdirRecursive(speedy_model_root());
    foreach (glob(speedy_resources_root() . '/*.php') ?: [] as $f) {
        if (basename($f) !== 'Resource.php') {
            unlink($f);
        }
    }
    ensureDir(speedy_request_root());
    ensureDir(speedy_response_root());
    ensureDir(speedy_model_root());

    /** @var array<string, list<array<string, mixed>>> */
    $byGroup = [];
    /** @var array<string, true> */
    $modelsToEmit = [];

    foreach ($catalog as $entry) {
        $group    = (string) $entry['group'];
        $reqClass = (string) $entry['request'];
        $byGroup[$group][] = $entry;

        if (! isset($allSchemas[$reqClass])) {
            throw new RuntimeException("Schema {$reqClass} not found in " . SPEEDY_SCHEMA_ROOT);
        }
        emitRequestDto($group, $reqClass, $allSchemas[$reqClass], $modelsToEmit);

        if ($entry['returns'] === 'json') {
            $respClass = (string) $entry['response'];
            if (! isset($allSchemas[$respClass])) {
                throw new RuntimeException("Schema {$respClass} not found in " . SPEEDY_SCHEMA_ROOT);
            }
            emitResponseDto($group, $respClass, $allSchemas[$respClass], $modelsToEmit);
        }
    }

    emitModels($modelsToEmit, $allSchemas);

    foreach ($byGroup as $group => $methods) {
        $code = renderResource($group, $methods);
        writeFile(speedy_resources_root() . '/' . $group . '.php', $code);
    }

    $groups = array_keys($byGroup);
    sort($groups);
    rewriteSpeedyRoot($groups);
    rewriteFacadeAnnotations($groups);

    echo "Generated " . count($catalog) . " operations across " . count($groups) . " groups.\n";
}

// Only run when invoked as the CLI entry point. The snapshot test
// `require_once`s this file and calls speedy_generate_main() itself after
// overriding the SPEEDY_* constants.
if (
    PHP_SAPI === 'cli'
    && isset($_SERVER['SCRIPT_FILENAME'])
    && realpath((string) $_SERVER['SCRIPT_FILENAME']) === __FILE__
) {
    speedy_generate_main();
}
```

- [ ] **Step 2: Syntax check**

Run: `php -l bin/generate.php`
Expected: "No syntax errors detected".

- [ ] **Step 3: Commit**

```bash
git add bin/generate.php
git commit -m "Add Resource and Speedy root emission to generator"
```

---

## Task 14: Run generator on seed; integration + roundtrip tests

**Files:**
- Run: `bin/generate.php`
- Create: `tests/Resources/ResourcesIntegrationTest.php`
- Create: `tests/Dto/RoundtripTest.php`

- [ ] **Step 1: Run the generator**

Run: `composer speedy:generate`
Expected: "Generated 10 operations across 8 groups." Files appear under `src/Resources/`, `src/Dto/Request/`, `src/Dto/Response/`, `src/Dto/Model/`.

- [ ] **Step 2: Verify generated code parses**

Run: `find src/Dto src/Resources -name '*.php' -print0 | xargs -0 -n1 php -l | grep -v 'No syntax errors'`
Expected: empty output (no syntax errors anywhere).

- [ ] **Step 3: Write `tests/Resources/ResourcesIntegrationTest.php`**

```php
<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\HttpFactory;
use Ux2Dev\Speedy\Config\SpeedyConfig;
use Ux2Dev\Speedy\Speedy;
use Ux2Dev\Speedy\Tests\Http\FakeHttpClient;

it('every catalog operation issues the right request and returns the typed response', function () {
    $catalog = json_decode(
        (string) file_get_contents(__DIR__ . '/../../bin/endpoints.json'),
        true,
        flags: JSON_THROW_ON_ERROR,
    );
    expect($catalog)->toBeArray()->not->toBeEmpty();

    foreach ($catalog as $entry) {
        $group     = $entry['group'];
        $name      = $entry['name'];
        $path      = $entry['path'];
        $method    = strtoupper($entry['method']);
        $reqClass  = 'Ux2Dev\\Speedy\\Dto\\Request\\' . $group . '\\' . $entry['request'];
        $accessor  = $group === 'PrintService' ? 'print' : lcfirst($group);

        $client = new FakeHttpClient();
        if ($entry['returns'] === 'bytes') {
            $client->queueBinary(200, '%PDF-1.4 fake', 'application/pdf');
        } else {
            $client->queueJson(200, []);
        }
        $factory = new HttpFactory();
        $config  = new SpeedyConfig(userName: 'demo', password: 'secret', language: 'EN');
        $speedy  = new Speedy($config, $client, $factory, $factory);

        $resource = $speedy->{$accessor}();
        $request  = new $reqClass();
        $result   = $resource->{$name}($request);

        expect($client->requests)->toHaveCount(1, "no request issued for {$group}::{$name}");
        expect((string) $client->requests[0]->getUri())->toBe('https://api.speedy.bg/v1' . $path, "wrong URI for {$group}::{$name}");
        expect($client->requests[0]->getMethod())->toBe($method, "wrong method for {$group}::{$name}");

        $body = json_decode((string) $client->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);
        expect($body['userName'])->toBe('demo', "auth not injected for {$group}::{$name}");
        expect($body['password'])->toBe('secret', "auth not injected for {$group}::{$name}");
        expect($body['language'])->toBe('EN', "language default not injected for {$group}::{$name}");

        if ($entry['returns'] === 'json') {
            $expectedClass = 'Ux2Dev\\Speedy\\Dto\\Response\\' . $group . '\\' . $entry['response'];
            expect($result)->toBeInstanceOf($expectedClass);
        } else {
            expect($result)->toBeInstanceOf(\Ux2Dev\Speedy\Http\PrintResult::class);
        }
    }
});

it('per-call language override wins over config default', function () {
    $client  = new FakeHttpClient();
    $client->queueJson(200, []);
    $factory = new HttpFactory();
    $config  = new SpeedyConfig(userName: 'u', password: 'p', language: 'EN');
    $speedy  = new Speedy($config, $client, $factory, $factory);

    $speedy->shipment()->create(
        new \Ux2Dev\Speedy\Dto\Request\Shipment\CreateShipmentRequest(),
        language: 'BG',
    );

    $body = json_decode((string) $client->requests[0]->getBody(), true);
    expect($body['language'])->toBe('BG');
});
```

- [ ] **Step 4: Write `tests/Dto/RoundtripTest.php`**

```php
<?php

declare(strict_types=1);

use Ux2Dev\Speedy\Dto\Request\Shipment\CreateShipmentRequest;
use Ux2Dev\Speedy\Dto\Response\Shipment\CreateShipmentResponse;

it('Request DTO toArray drops null fields', function () {
    $req = new CreateShipmentRequest(ref1: 'ABC');

    $arr = $req->toArray();

    expect($arr)->toHaveKey('ref1');
    expect($arr['ref1'])->toBe('ABC');
    expect($arr)->not->toHaveKey('ref2');
});

it('Response DTO fromArray reads null fields safely', function () {
    $resp = CreateShipmentResponse::fromArray([]);

    expect($resp)->toBeInstanceOf(CreateShipmentResponse::class);
});
```

- [ ] **Step 5: Run all tests**

Run: `vendor/bin/pest`
Expected: PASS — every transport, config, integration, roundtrip test green.

- [ ] **Step 6: Commit**

```bash
git add src/Speedy.php src/Resources/*.php src/Dto tests/Resources/ResourcesIntegrationTest.php tests/Dto/RoundtripTest.php
git commit -m "Generate seed Resources and DTOs; add integration and roundtrip tests"
```

---

## Task 15: Generator snapshot test

**Files:** Create `tests/Bin/GeneratorSnapshotTest.php`.

The test invokes the generator in-process by `define()`-ing the path constants before `require_once`. Avoids any shell-out.

- [ ] **Step 1: Write the snapshot test**

```php
<?php

declare(strict_types=1);

it('generator output matches committed src/', function () {
    $repoRoot = realpath(__DIR__ . '/../..');
    $tmp      = sys_get_temp_dir() . '/speedy-gen-' . bin2hex(random_bytes(6));

    mkdir($tmp);
    mkdir($tmp . '/src/Resources', 0777, true);
    mkdir($tmp . '/src/Laravel/Facades', 0777, true);

    // Replicate the generator's inputs and the hand-written files it preserves
    // (Resource.php base + Speedy.php skeleton + Facade if present).
    copy($repoRoot . '/src/Resources/Resource.php', $tmp . '/src/Resources/Resource.php');
    copy($repoRoot . '/src/Speedy.php', $tmp . '/src/Speedy.php');
    if (file_exists($repoRoot . '/src/Laravel/Facades/Speedy.php')) {
        copy($repoRoot . '/src/Laravel/Facades/Speedy.php', $tmp . '/src/Laravel/Facades/Speedy.php');
    }

    // Override generator paths and run it in-process.
    if (! defined('SPEEDY_SRC_ROOT'))    define('SPEEDY_SRC_ROOT', $tmp . '/src');
    if (! defined('SPEEDY_SCHEMA_ROOT')) define('SPEEDY_SCHEMA_ROOT', $repoRoot . '/bin/schemas');
    if (! defined('SPEEDY_CATALOG_PATH')) define('SPEEDY_CATALOG_PATH', $repoRoot . '/bin/endpoints.json');

    require_once $repoRoot . '/bin/generate.php';
    speedy_generate_main();

    foreach (['src/Resources', 'src/Dto/Request', 'src/Dto/Response', 'src/Dto/Model'] as $sub) {
        $committed = $repoRoot . '/' . $sub;
        $regen     = $tmp . '/' . $sub;
        if (! is_dir($committed)) {
            continue;
        }
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($committed));
        foreach ($rii as $file) {
            if ($file->isDir()) continue;
            $rel = substr((string) $file, strlen($committed) + 1);
            $expected = file_get_contents((string) $file);
            $actual   = file_get_contents($regen . '/' . $rel);
            expect($actual)->toBe($expected, "Drift in {$sub}/{$rel}");
        }
    }
});
```

Note: this test suite is `--filter`-able. Because `define()` is per-process, run this test in isolation (`vendor/bin/pest tests/Bin/GeneratorSnapshotTest.php`) when you suspect the constants need re-defining; the integration test in Task 14 runs in a separate test invocation by default in CI — the snapshot test must be in its own process. To enforce that, configure the snapshot test under a separate phpunit testsuite (Step 2) so CI runs it standalone.

- [ ] **Step 2: Add a dedicated testsuite to `phpunit.xml`** so the snapshot test always runs in a fresh process.

Replace the `<testsuites>` block in `phpunit.xml` with:

```xml
<testsuites>
    <testsuite name="Speedy">
        <directory>tests</directory>
        <exclude>tests/Bin</exclude>
    </testsuite>
    <testsuite name="Generator">
        <directory>tests/Bin</directory>
    </testsuite>
</testsuites>
```

`vendor/bin/pest` runs both suites in sequence; the generator suite gets a fresh PHP process so the `define()` calls are not stuck from earlier tests.

- [ ] **Step 3: Run it**

Run: `vendor/bin/pest --testsuite=Generator`
Expected: PASS, 1 test.

- [ ] **Step 4: Commit**

```bash
git add tests/Bin/GeneratorSnapshotTest.php phpunit.xml
git commit -m "Add generator snapshot test isolated in its own testsuite"
```

---

## Task 16: Expand `bin/endpoints.json` to full catalog

This is the catalog-authoring task. Open Speedy's docs at `https://api.speedy.bg/api/docs/`, walk every service group, and extract `{group, name, method, path, request, response, returns}` for every operation. Cross-reference with `bin/schemas/*.schema.json` to confirm the simple names exist.

**Files:** Modify `bin/endpoints.json`.

- [ ] **Step 1: Map every Speedy service group to its operations**

Use this table as a starting point. For each row, verify the schema names exist in `bin/schemas/` and that the path matches the docs. Treat any path the docs disagree with as a TODO at this stage — the engineer can verify with live credentials when wiring the SDK in.

```text
group         | name                        | method | path                                 | request                        | response                        | returns
--------------|-----------------------------|--------|--------------------------------------|--------------------------------|---------------------------------|--------
Shipment      | create                      | POST   | /shipment                            | CreateShipmentRequest          | CreateShipmentResponse          | json
Shipment      | cancel                      | POST   | /shipment/cancel                     | CancelShipmentRequest          | CancelShipmentResponse          | json
Shipment      | addParcel                   | POST   | /shipment/add_parcel                 | AddParcelRequest               | AddParcelResponse               | json
Shipment      | finalize                    | POST   | /shipment/finalize                   | FinalizePendingShipmentRequest | FinalizePendingShipmentResponse | json
Shipment      | info                        | POST   | /shipment/info                       | ShipmentInformationRequest     | ShipmentInformationResponse     | json
Shipment      | secondary                   | POST   | /shipment/secondary                  | SecondaryShipmentsRequest      | SecondaryShipmentsResponse      | json
Shipment      | update                      | POST   | /shipment/update                     | UpdateShipmentRequest          | UpdateShipmentResponse          | json
Shipment      | updateProperties            | POST   | /shipment/update/properties          | UpdateShipmentPropertiesRequest| UpdateShipmentResponse          | json
Shipment      | search                      | POST   | /shipment/search                     | FindParcelsByRefRequest        | FindParcelsByRefResponse        | json
Shipment      | handover                    | POST   | /shipment/handover                   | HandOverToCourierRequest       | HandOverToCourierResponse       | json
Shipment      | handoverToMidwayCarrier     | POST   | /shipment/handover-to-midway-carrier | HandOverToMidwayCarrierRequest | HandOverToMidwayCarrierResponse | json
Shipment      | barcodeInformation          | POST   | /shipment/barcode-information        | BarcodeInformationRequest      | BarcodeInformationResponse      | json
PrintService  | voucher                     | POST   | /print                               | PrintVoucherRequest            | null                            | bytes
PrintService  | extended                    | POST   | /print/extended                      | PrintRequest                   | ExtendedPrintResponse           | json
PrintService  | labelInfo                   | POST   | /print/labelInfo                     | LabelInfoRequest               | LabelInfoResponse               | json
Track         | track                       | POST   | /track                               | TrackRequest                   | TrackResponse                   | json
Track         | bulkFiles                   | POST   | /track/bulk                          | BulkTrackingDataFilesRequest   | BulkTrackingDataFilesResponse   | json
Pickup        | terms                       | POST   | /pickup/terms                        | PickupTermsRequest             | PickupTermsResponse             | json
Pickup        | request                     | POST   | /pickup                              | PickupRequest                  | PickupResponse                  | json
Location      | findCountry                 | POST   | /location/country                    | FindCountryRequest             | FindCountryResponse             | json
Location      | getCountry                  | POST   | /location/country/getById            | GetCountryRequest              | GetCountryResponse              | json
Location      | getAllCountries             | POST   | /location/country/getAll             | GetAllCountriesRequest         | FindCountryResponse             | json
Location      | getAllStates                | POST   | /location/state                      | GetAllStatesRequest            | FindCountryResponse             | json
Location      | findSite                    | POST   | /location/site                       | FindSiteRequest                | FindSiteResponse                | json
Location      | getSite                     | POST   | /location/site/getById               | GetSiteRequest                 | GetSiteResponse                 | json
Location      | getAllSites                 | POST   | /location/site/getAll                | GetAllSitesRequest             | FindSiteResponse                | json
Location      | getAllPostcodes             | POST   | /location/postcode                   | GetAllPostcodesRequest         | FindSiteResponse                | json
Location      | findStreet                  | POST   | /location/street                     | FindStreetRequest              | FindStreetResponse              | json
Location      | getStreet                   | POST   | /location/street/getById             | GetStreetRequest               | GetStreetResponse               | json
Location      | getAllStreets               | POST   | /location/street/getAll              | GetAllStreetsRequest           | FindStreetResponse              | json
Location      | findOffice                  | POST   | /location/office                     | FindOfficeRequest              | FindOfficeResponse              | json
Location      | findNearestOffices          | POST   | /location/office/nearest             | FindNearestOfficesRequest      | FindNearestOfficesResponse      | json
Location      | getOffice                   | POST   | /location/office/getById             | GetOfficeRequest               | GetOfficeResponse               | json
Location      | getAllPointOfInterests      | POST   | /location/poi                        | GetAllPointOfInterestsRequest  | FindSiteResponse                | json
Location      | searchAddress               | POST   | /location/address                    | SearchAddressRequest           | SearchAddressResponse           | json
Calculate     | calculate                   | POST   | /calculate                           | CalculationRequest             | CalculationResponse             | json
Clients       | getOwnClientId              | POST   | /client                              | GetOwnClientIdRequest          | GetOwnClientIdResponse          | json
Clients       | getClient                   | POST   | /client/getById                      | GetClientRequest               | GetClientResponse               | json
Clients       | getContractClients          | POST   | /client/contract                     | GetContractClientsRequest      | GetContractClientsResponse      | json
Clients       | createContact               | POST   | /client/contact                      | CreateContactRequest           | CreateContactResponse           | json
Clients       | getContactByExternalId      | POST   | /client/contact/getByExternalId      | GetContactByExternalIdRequest  | GetContactByExternalIdResponse  | json
Clients       | contractInfo                | POST   | /client/contractInfo                 | ContractInfoRequest            | ContractInfo                    | json
Validation    | validateAddress             | POST   | /validation/address                  | ValidateAddressRequest         | ValidationResponse              | json
Validation    | validatePostCode            | POST   | /validation/postCode                 | ValidatePostCodeRequest        | ValidationResponse              | json
Validation    | validatePhone               | POST   | /validation/phone                    | ValidatePhoneRequest           | ValidationResponse              | json
Services      | destinationServices         | POST   | /services/destination                | DestinationServicesRequest     | DestinationServicesResponse     | json
Services      | services                    | POST   | /services                            | ServicesRequest                | ServicesResponse                | json
Payments      | payouts                     | POST   | /payments                            | PayoutRequest                  | Payout                          | json
```

- [ ] **Step 2: Author `bin/endpoints.json`** with all entries

Replace `bin/endpoints.json` with the JSON-array equivalent of the table above. Each entry follows the same shape as the seed:

```json
{
    "group":    "Shipment",
    "name":     "create",
    "method":   "POST",
    "path":     "/shipment",
    "request":  "CreateShipmentRequest",
    "response": "CreateShipmentResponse",
    "returns":  "json"
}
```

- [ ] **Step 3: Regenerate**

Run: `composer speedy:generate`
Expected: "Generated N operations across 10 groups." (N matches the table row count after reconciling.)

- [ ] **Step 4: Run all tests**

Run: `vendor/bin/pest`
Expected: PASS — `ResourcesIntegrationTest` automatically covers every catalog entry; `Generator` testsuite confirms output is stable.

- [ ] **Step 5: Commit**

```bash
git add bin/endpoints.json src/Resources src/Dto src/Speedy.php
git commit -m "Expand endpoints catalog to full Speedy v1 surface"
```

---

## Task 17: Tier 2 Laravel — config publish stub

**Files:** Create `src/Laravel/config/speedy.php`.

- [ ] **Step 1: Write the config**

```php
<?php

declare(strict_types=1);

return [
    'default'  => env('SPEEDY_DEFAULT_ACCOUNT', 'main'),

    'accounts' => [
        'main' => [
            'base_url'         => env('SPEEDY_BASE_URL', 'https://api.speedy.bg/v1'),
            'user_name'        => env('SPEEDY_USERNAME'),
            'password'         => env('SPEEDY_PASSWORD'),
            'language'         => env('SPEEDY_LANGUAGE'),
            'client_system_id' => env('SPEEDY_CLIENT_SYSTEM_ID') === null
                ? null
                : (int) env('SPEEDY_CLIENT_SYSTEM_ID'),
            'timeout'          => (int) env('SPEEDY_TIMEOUT', 30),
        ],
    ],

    'nomenclatures' => [
        'enabled'  => env('SPEEDY_SYNC_NOMENCLATURES', false),
        'entities' => ['countries', 'states', 'sites', 'streets', 'postcodes', 'offices'],
        'schedule' => '0 3 * * *',
    ],
    'shipments' => [
        'enabled'      => env('SPEEDY_PERSIST_SHIPMENTS', false),
        'auto_persist' => env('SPEEDY_AUTO_PERSIST', false),
    ],
    'tracking' => [
        'enabled'    => env('SPEEDY_TRACK_SHIPMENTS', false),
        'poll_batch' => 200,
        'schedule'   => '*/15 * * * *',
    ],
];
```

- [ ] **Step 2: Commit**

```bash
git add src/Laravel/config/speedy.php
git commit -m "Add Laravel config stub for Speedy"
```

---

## Task 18: Tier 2 Laravel — SpeedyManager

**Files:**
- Create: `src/Laravel/SpeedyManager.php`
- Create: `tests/Laravel/TestCase.php`
- Create: `tests/Laravel/SpeedyManagerTest.php`

- [ ] **Step 1: Write `tests/Laravel/TestCase.php`**

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Tests\Laravel;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Ux2Dev\Speedy\Laravel\SpeedyServiceProvider;

abstract class TestCase extends BaseTestCase
{
    /** @return array<class-string> */
    protected function getPackageProviders($app): array
    {
        return [SpeedyServiceProvider::class];
    }
}
```

- [ ] **Step 2: Write `tests/Laravel/SpeedyManagerTest.php`**

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Tests\Laravel;

use Ux2Dev\Speedy\Exception\ConfigurationException;
use Ux2Dev\Speedy\Laravel\SpeedyManager;
use Ux2Dev\Speedy\Speedy;

uses(TestCase::class);

it('resolves the default account', function () {
    config()->set('speedy.default', 'main');
    config()->set('speedy.accounts.main', [
        'base_url'  => 'https://api.speedy.bg/v1',
        'user_name' => 'demo',
        'password'  => 'secret',
    ]);

    $manager = $this->app->make(SpeedyManager::class);

    expect($manager->currentAccount())->toBe('main');
    expect($manager->instance())->toBeInstanceOf(Speedy::class);
});

it('account() returns an immutable clone with the target account active', function () {
    config()->set('speedy.default', 'main');
    config()->set('speedy.accounts', [
        'main'  => ['base_url' => 'https://api.speedy.bg/v1', 'user_name' => 'm', 'password' => 'mp'],
        'other' => ['base_url' => 'https://api.speedy.bg/v1', 'user_name' => 'o', 'password' => 'op'],
    ]);

    $manager = $this->app->make(SpeedyManager::class);
    $other   = $manager->account('other');

    expect($other)->not->toBe($manager);
    expect($manager->currentAccount())->toBe('main');
    expect($other->currentAccount())->toBe('other');
});

it('throws on unknown account', function () {
    config()->set('speedy.accounts.main', ['base_url' => 'https://api.speedy.bg/v1', 'user_name' => 'm', 'password' => 'mp']);

    $manager = $this->app->make(SpeedyManager::class);

    expect(fn () => $manager->account('ghost')->instance())
        ->toThrow(ConfigurationException::class);
});

it('caches the Speedy instance per account', function () {
    config()->set('speedy.accounts.main', ['base_url' => 'https://api.speedy.bg/v1', 'user_name' => 'm', 'password' => 'mp']);

    $manager = $this->app->make(SpeedyManager::class);

    expect($manager->instance())->toBe($manager->instance());
});

it('forwards undefined methods to the active Speedy', function () {
    config()->set('speedy.accounts.main', ['base_url' => 'https://api.speedy.bg/v1', 'user_name' => 'm', 'password' => 'mp']);

    $manager  = $this->app->make(SpeedyManager::class);
    $resource = $manager->shipment();

    expect($resource)->toBeInstanceOf(\Ux2Dev\Speedy\Resources\Shipment::class);
});
```

- [ ] **Step 3: Run tests to verify they fail**

Run: `vendor/bin/pest tests/Laravel/SpeedyManagerTest.php`
Expected: FAIL — `SpeedyServiceProvider` and/or `SpeedyManager` not found.

- [ ] **Step 4: Write `src/Laravel/SpeedyManager.php`**

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Laravel;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Ux2Dev\Speedy\Config\SpeedyConfig;
use Ux2Dev\Speedy\Exception\ConfigurationException;
use Ux2Dev\Speedy\Speedy;

final class SpeedyManager
{
    /** @var array<string, Speedy> */
    private array $instances = [];

    private string $currentAccount;

    /** @param array<string, mixed> $config */
    public function __construct(
        private readonly array $config,
        private readonly ?ClientInterface $httpClient = null,
        private readonly ?RequestFactoryInterface $requestFactory = null,
        private readonly ?StreamFactoryInterface $streamFactory = null,
    ) {
        $this->currentAccount = (string) ($config['default'] ?? 'main');
    }

    public function account(string $name): self
    {
        $clone = clone $this;
        $clone->currentAccount = $name;
        return $clone;
    }

    public function currentAccount(): string
    {
        return $this->currentAccount;
    }

    public function instance(): Speedy
    {
        return $this->instances[$this->currentAccount] ??= $this->build($this->currentAccount);
    }

    /** @param array<int, mixed> $arguments */
    public function __call(string $method, array $arguments): mixed
    {
        return $this->instance()->{$method}(...$arguments);
    }

    private function build(string $account): Speedy
    {
        $accounts = (array) ($this->config['accounts'] ?? []);

        if (! isset($accounts[$account]) || ! is_array($accounts[$account])) {
            throw new ConfigurationException("Speedy account \"{$account}\" is not configured");
        }

        $c = $accounts[$account];

        $config = new SpeedyConfig(
            baseUrl:        (string) ($c['base_url'] ?? 'https://api.speedy.bg/v1'),
            userName:       (string) ($c['user_name'] ?? ''),
            password:       (string) ($c['password'] ?? ''),
            language:       isset($c['language']) ? (string) $c['language'] : null,
            clientSystemId: isset($c['client_system_id']) ? (int) $c['client_system_id'] : null,
            timeout:        (int) ($c['timeout'] ?? 30),
        );

        $factory = new HttpFactory();

        return new Speedy(
            $config,
            $this->httpClient ?? new Client(['timeout' => $config->timeout]),
            $this->requestFactory ?? $factory,
            $this->streamFactory ?? $factory,
        );
    }
}
```

- [ ] **Step 5: Tests will still fail until Task 19 wires the ServiceProvider — that's expected. Continue.**

- [ ] **Step 6: Commit**

```bash
git add src/Laravel/SpeedyManager.php tests/Laravel/TestCase.php tests/Laravel/SpeedyManagerTest.php
git commit -m "Add SpeedyManager for multi-account Laravel resolution"
```

---

## Task 19: Tier 2 Laravel — ServiceProvider

**Files:**
- Create: `src/Laravel/SpeedyServiceProvider.php`
- Create: `tests/Laravel/PublishConfigTest.php`

- [ ] **Step 1: Write `src/Laravel/SpeedyServiceProvider.php`**

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Laravel;

use Illuminate\Support\ServiceProvider;

final class SpeedyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/speedy.php', 'speedy');

        $this->app->singleton(SpeedyManager::class, function ($app) {
            return new SpeedyManager((array) $app['config']->get('speedy', []));
        });

        $this->app->alias(SpeedyManager::class, 'speedy');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/speedy.php' => config_path('speedy.php'),
            ], 'speedy-config');
        }
    }
}
```

- [ ] **Step 2: Write `tests/Laravel/PublishConfigTest.php`**

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Tests\Laravel;

uses(TestCase::class);

it('registers the speedy config tag and publishes the file', function () {
    $target = config_path('speedy.php');

    if (file_exists($target)) {
        unlink($target);
    }

    $this->artisan('vendor:publish', ['--tag' => 'speedy-config'])->assertExitCode(0);

    expect(file_exists($target))->toBeTrue();

    unlink($target);
});
```

- [ ] **Step 3: Run all Laravel tests**

Run: `vendor/bin/pest tests/Laravel/`
Expected: PASS — `SpeedyManagerTest` and `PublishConfigTest` all green.

- [ ] **Step 4: Commit**

```bash
git add src/Laravel/SpeedyServiceProvider.php tests/Laravel/PublishConfigTest.php
git commit -m "Add SpeedyServiceProvider with config publish tag"
```

---

## Task 20: Tier 2 Laravel — Facade with regenerated annotations

**Files:**
- Create: `src/Laravel/Facades/Speedy.php`
- Create: `tests/Laravel/FacadeTest.php`
- Run: `composer speedy:generate` to refresh `@method` annotations.

- [ ] **Step 1: Write `src/Laravel/Facades/Speedy.php`** (initial — annotations get regenerated)

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Ux2Dev\Speedy\Laravel\SpeedyManager;

/**
 * Annotations regenerated by bin/generate.php — do not edit by hand.
 */
final class Speedy extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SpeedyManager::class;
    }
}
```

- [ ] **Step 2: Re-run the generator to inject `@method` annotations**

Run: `composer speedy:generate`
Expected: the doc-block above the class declaration now lists `@method static` lines for `instance()`, `account()`, and every resource accessor.

- [ ] **Step 3: Write `tests/Laravel/FacadeTest.php`**

```php
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Tests\Laravel;

use Ux2Dev\Speedy\Laravel\Facades\Speedy;
use Ux2Dev\Speedy\Laravel\SpeedyManager;

uses(TestCase::class);

it('resolves to the manager', function () {
    config()->set('speedy.accounts.main', ['base_url' => 'https://api.speedy.bg/v1', 'user_name' => 'm', 'password' => 'mp']);

    expect(Speedy::getFacadeRoot())->toBeInstanceOf(SpeedyManager::class);
});

it('forwards calls to the active Speedy via the manager', function () {
    config()->set('speedy.accounts.main', ['base_url' => 'https://api.speedy.bg/v1', 'user_name' => 'm', 'password' => 'mp']);

    expect(Speedy::shipment())->toBeInstanceOf(\Ux2Dev\Speedy\Resources\Shipment::class);
});
```

- [ ] **Step 4: Run all tests**

Run: `vendor/bin/pest`
Expected: PASS — entire suite green across both `Speedy` and `Generator` testsuites.

- [ ] **Step 5: Commit**

```bash
git add src/Laravel/Facades/Speedy.php tests/Laravel/FacadeTest.php
git commit -m "Add Speedy facade with regenerated method annotations"
```

---

## Task 21: README and final coverage push

**Files:** Modify `README.md`.

- [ ] **Step 1: Write the full README**

Replace `README.md` with:

```markdown
# Speedy PHP SDK

> **Warning:** Developer testing version of the library — use at your own risk.

Framework-agnostic PHP SDK for the [Speedy](https://api.speedy.bg/api/docs/) courier API at `https://api.speedy.bg/v1`. Covers all ten Speedy service groups (Shipment, Print, Track, Pickup, Location, Calculate, Client, Validation, Services, Payments) as resource methods that return typed Response DTOs. Works with plain PHP or Laravel.

## Requirements

- PHP 8.2 or higher
- JSON extension
- A PSR-18 HTTP client and PSR-17 request/stream factories (Guzzle provides both)

## Installation

```bash
composer require ux2dev/speedy
```

## Quick start — plain PHP

```php
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Ux2Dev\Speedy\Config\SpeedyConfig;
use Ux2Dev\Speedy\Speedy;
use Ux2Dev\Speedy\Dto\Request\Shipment\CreateShipmentRequest;

$config = new SpeedyConfig(
    userName: 'your-username',
    password: 'your-password',
    language: 'EN',
);

$factory = new HttpFactory();
$speedy  = new Speedy($config, new Client(), $factory, $factory);

$response = $speedy->shipment()->create(new CreateShipmentRequest(
    ref1: 'ORDER-1234',
));

echo $response->id;
```

## Quick start — Laravel

The package auto-registers `SpeedyServiceProvider` and a `Speedy` facade.

Publish the config:

```bash
php artisan vendor:publish --tag=speedy-config
```

Set credentials in `.env`:

```ini
SPEEDY_USERNAME=demo
SPEEDY_PASSWORD=secret
SPEEDY_LANGUAGE=EN
```

Use the facade:

```php
use Ux2Dev\Speedy\Laravel\Facades\Speedy;
use Ux2Dev\Speedy\Dto\Request\Shipment\CreateShipmentRequest;

$response = Speedy::shipment()->create(new CreateShipmentRequest(ref1: 'ORDER-1234'));
```

### Multiple accounts

```php
return [
    'default'  => 'main',
    'accounts' => [
        'main'   => ['user_name' => env('SPEEDY_USERNAME'),   'password' => env('SPEEDY_PASSWORD'),   'language' => 'EN'],
        'second' => ['user_name' => env('SPEEDY_USERNAME_2'), 'password' => env('SPEEDY_PASSWORD_2'), 'language' => 'BG'],
    ],
];
```

```php
Speedy::account('second')->shipment()->create($req);
```

`account()` returns an immutable clone — the default stays untouched.

## How the SDK is organised

| Layer | Location | Purpose |
|-------|----------|---------|
| Config | `Ux2Dev\Speedy\Config\SpeedyConfig` | Base URL + credentials, validated, password redacted |
| Transport | `Ux2Dev\Speedy\Http\SpeedyTransport` | PSR-18 dispatch, auth auto-injection, error mapping |
| Request DTOs | `Ux2Dev\Speedy\Dto\Request\{Group}\*Request` | Generated, `toArray()` |
| Response DTOs | `Ux2Dev\Speedy\Dto\Response\{Group}\*Response` | Generated, `fromArray()` |
| Model DTOs | `Ux2Dev\Speedy\Dto\Model\*` | Shared entities (Address, Office, Site, ...) |
| Resources | `Ux2Dev\Speedy\Resources\{Group}` | Generated, one method per operation |
| Root client | `Ux2Dev\Speedy\Speedy` | Aggregator |
| Laravel | `Ux2Dev\Speedy\Laravel\*` | Service Provider + multi-account Manager + Facade |

Generated from `bin/endpoints.json` (a hand-curated catalog) plus `bin/schemas/` (a snapshot of `https://api.speedy.bg/v1/schema`) by `bin/generate.php`.

## Print Service

Print endpoints return raw bytes; the SDK wraps them in a `PrintResult`:

```php
$result = $speedy->print()->voucher(new PrintVoucherRequest(parcels: [/* ... */]));

$result->isPdf();              // bool
$result->saveTo('/tmp/v.pdf'); // returns bytes written
$result->bytes();              // raw payload
```

## Errors

Successful return paths always carry a "good" Response DTO. When the API responds with a populated `error` field, the transport throws `Ux2Dev\Speedy\Exception\ApiException` with structured fields (`code`, `apiMessage`, `context`, `errorId`, `component`, `httpStatus`, full `body`).

| Exception | When |
|-----------|------|
| `ConfigurationException` | Invalid `SpeedyConfig` input or unknown Laravel account |
| `TransportException` | PSR-18 client failure (network, timeout) |
| `InvalidResponseException` | Empty body, malformed JSON, or unexpected envelope |
| `ApiException` | Speedy returned a non-null `error` field |

All extend `SpeedyException`.

## Regenerating the SDK

```bash
composer speedy:fetch-schemas    # snapshots /v1/schema into bin/schemas
composer speedy:generate         # rewrites src/Resources, src/Dto, and the Speedy/Facade method blocks
vendor/bin/pest                  # snapshot test catches drift
```

## Testing

```bash
composer install
vendor/bin/pest
XDEBUG_MODE=coverage vendor/bin/pest --coverage --min=100
```

The suite mocks a PSR-18 client to exercise every resource method end-to-end.

## License

MIT
```

- [ ] **Step 2: Run coverage**

Run: `XDEBUG_MODE=coverage vendor/bin/pest --coverage --min=100`
Expected: 100% line coverage on `src/`. If anything's below, add the missing tests in this same task before committing.

- [ ] **Step 3: Commit**

```bash
git add README.md
git commit -m "Add full README and confirm 100% coverage"
```

---

## Self-review

**Spec coverage check (against `docs/superpowers/specs/2026-04-29-speedy-sdk-design.md`):**

- Config (final readonly, validated, password redacted, serialize-blocked) → Task 3
- SpeedyTransport (PSR-18, auth auto-injection, four send methods, error mapping) → Task 6
- PrintResult (bytes, contentType, filename, saveTo, isPdf, isZpl) → Task 4
- ResultList → Task 4
- Exception hierarchy with structured ApiException fields → Task 2
- Resource base + Speedy root with regenerable accessor block → Tasks 5, 13
- Generator: bin/schemas snapshot + bin/endpoints.json + bin/generate.php → Tasks 7, 8, 9, 10, 11, 12, 13
- Generator emits Request/Response/Model DTOs and Resources, regenerates Speedy root and Facade annotations → Tasks 13, 20
- Tier 2 SpeedyManager (multi-account, immutable account() clone, lazy caching, __call forwarding) → Task 18
- Tier 2 SpeedyServiceProvider (auto-register, config merge, publish tag) → Task 19
- Tier 2 Facade with `@method` annotations regenerated → Task 20
- Naming: `instance()` on Manager, `clients()` on resource accessor (full catalog in Task 16), `PrintService` class with `print()` accessor (`groupAccessor()` helper in Task 13) → consistent throughout
- Tests: FakeHttpClient, transport, config, integration, generator snapshot, roundtrip, manager, facade, publish → Tasks 4, 6, 14, 15, 18, 19, 20
- README + 100% coverage gate → Task 21

Tier 3 (Nomenclatures, Shipments, Tracking) deferred to subsequent plans, per the decomposition. **No spec gaps for the Tier 1 + Tier 2 scope of this plan.**

**Placeholder scan:** No `TBD`, no `TODO`, no "implement later", no "similar to Task N" without the code. Catalog rows in Task 16 are explicit; the engineer reconciles paths against live docs before committing.

**Type consistency:**
- `SpeedyTransport::postJson/getJson/deleteJson/postBinary` — all four methods named consistently throughout (Task 6, Task 13).
- `SpeedyConfig::getPassword()` — used in transport's `mergeAuth()` (Task 6) — consistent.
- `ApiException::$apiMessage` (not `$message`, which shadows the parent) — used consistently in tests and transport (Tasks 2, 6).
- `SpeedyManager::instance()` — referenced in tests (Task 18) and facade annotations (Task 20).
- `groupAccessor()` helper (Task 13) — same logic as `lcfirst($group === 'PrintService' ? 'Print' : $group)` used in the integration test (Task 14). Consistent.
- `speedy_generate_main()` — the entry point function name is the same in Tasks 13, 15.
- `SPEEDY_SRC_ROOT`, `SPEEDY_SCHEMA_ROOT`, `SPEEDY_CATALOG_PATH` constants — used identically across Tasks 9 and 15.

No issues found.

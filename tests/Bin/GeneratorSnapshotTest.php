<?php

declare(strict_types=1);

use Ux2Dev\Speedy\Generator\Generator;

it('generator output matches committed src/', function () {
    $repoRoot = realpath(__DIR__ . '/../..');
    $tmp      = sys_get_temp_dir() . '/speedy-gen-' . bin2hex(random_bytes(6));

    mkdir($tmp);
    mkdir($tmp . '/src/Resources', 0777, true);
    mkdir($tmp . '/src/Laravel/Facades', 0777, true);

    // Replicate the generator's inputs and the hand-written files it preserves
    // (Resource.php base + Speedy.php skeleton + Facade if present).
    copy($repoRoot . '/src/Resources/Resource.php', $tmp . '/src/Resources/Resource.php');

    // Use a fresh Speedy.php skeleton (markers blank) so the generator has
    // somewhere to write into. The committed src/Speedy.php already has
    // generated content; we want to start from a clean slate to verify
    // the rewrite is deterministic.
    file_put_contents($tmp . '/src/Speedy.php', speedyRootSkeleton());

    if (file_exists($repoRoot . '/src/Laravel/Facades/Speedy.php')) {
        copy($repoRoot . '/src/Laravel/Facades/Speedy.php', $tmp . '/src/Laravel/Facades/Speedy.php');
    }

    require_once $repoRoot . '/bin/generate.php';

    $generator = new Generator(
        srcRoot:     $tmp . '/src',
        schemaRoot:  $repoRoot . '/bin/schemas',
        catalogPath: $repoRoot . '/bin/endpoints.json',
    );
    $generator->run();

    foreach (['src/Resources', 'src/Dto/Request', 'src/Dto/Response', 'src/Dto/Model', 'src/Speedy.php'] as $sub) {
        $committed = $repoRoot . '/' . $sub;
        $regen     = $tmp . '/' . $sub;

        if (is_file($committed)) {
            expect(file_get_contents($regen))->toBe(file_get_contents($committed), "Drift in {$sub}");
            continue;
        }
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

function speedyRootSkeleton(): string
{
    return <<<'PHP'
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

PHP;
}

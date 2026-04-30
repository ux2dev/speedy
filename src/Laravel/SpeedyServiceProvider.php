<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Laravel;

use Illuminate\Http\Client\Factory as LaravelHttpFactory;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use RuntimeException;

final class SpeedyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/speedy.php', 'speedy');

        $this->app->bindIf(RequestFactoryInterface::class, static function () {
            return self::resolvePsr17Factory();
        });
        $this->app->bindIf(StreamFactoryInterface::class, static function () {
            return self::resolvePsr17Factory();
        });

        $this->app->singleton(SpeedyManager::class, function ($app) {
            return new SpeedyManager(
                config:         (array) $app['config']->get('speedy', []),
                httpFactory:    $app->make(LaravelHttpFactory::class),
                requestFactory: $app->make(RequestFactoryInterface::class),
                streamFactory:  $app->make(StreamFactoryInterface::class),
            );
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

    /**
     * Default PSR-17 factory resolver. Laravel ships with guzzlehttp/psr7
     * transitively (via guzzlehttp/guzzle), so HttpFactory is the obvious
     * fallback. Apps that bind RequestFactoryInterface/StreamFactoryInterface
     * themselves win because we use bindIf.
     *
     * @return RequestFactoryInterface&StreamFactoryInterface
     */
    private static function resolvePsr17Factory(): object
    {
        $fqcn = '\\GuzzleHttp\\Psr7\\HttpFactory';

        // @codeCoverageIgnoreStart
        if (! class_exists($fqcn)) {
            throw new RuntimeException(
                'Speedy: no PSR-17 factory available. Bind RequestFactoryInterface and '
                . 'StreamFactoryInterface in your application or install guzzlehttp/psr7.'
            );
        }
        // @codeCoverageIgnoreEnd

        /** @var RequestFactoryInterface&StreamFactoryInterface $factory */
        $factory = new $fqcn();
        return $factory;
    }
}

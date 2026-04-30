<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Tests\Laravel;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Ux2Dev\Speedy\Laravel\SpeedyServiceProvider;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [SpeedyServiceProvider::class];
    }
}

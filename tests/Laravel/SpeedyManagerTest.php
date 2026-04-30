<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Tests\Laravel;

use Ux2Dev\Speedy\Exception\ConfigurationException;
use Ux2Dev\Speedy\Laravel\SpeedyManager;
use Ux2Dev\Speedy\Speedy;

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

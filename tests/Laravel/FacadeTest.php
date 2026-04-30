<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Tests\Laravel;

use Ux2Dev\Speedy\Laravel\Facades\Speedy;
use Ux2Dev\Speedy\Laravel\SpeedyManager;

it('resolves to the manager', function () {
    config()->set('speedy.accounts.main', ['base_url' => 'https://api.speedy.bg/v1', 'user_name' => 'm', 'password' => 'mp']);

    expect(Speedy::getFacadeRoot())->toBeInstanceOf(SpeedyManager::class);
});

it('forwards calls to the active Speedy via the manager', function () {
    config()->set('speedy.accounts.main', ['base_url' => 'https://api.speedy.bg/v1', 'user_name' => 'm', 'password' => 'mp']);

    expect(Speedy::shipment())->toBeInstanceOf(\Ux2Dev\Speedy\Resources\Shipment::class);
});

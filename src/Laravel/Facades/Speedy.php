<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Ux2Dev\Speedy\Laravel\SpeedyManager;

/**
 * @method static \Ux2Dev\Speedy\Speedy instance()
 * @method static \Ux2Dev\Speedy\Laravel\SpeedyManager account(string $name)
 * @method static \Ux2Dev\Speedy\Resources\Calculate calculate()
 * @method static \Ux2Dev\Speedy\Resources\Clients clients()
 * @method static \Ux2Dev\Speedy\Resources\Location location()
 * @method static \Ux2Dev\Speedy\Resources\Payments payments()
 * @method static \Ux2Dev\Speedy\Resources\Pickup pickup()
 * @method static \Ux2Dev\Speedy\Resources\PrintService print()
 * @method static \Ux2Dev\Speedy\Resources\Services services()
 * @method static \Ux2Dev\Speedy\Resources\Shipment shipment()
 * @method static \Ux2Dev\Speedy\Resources\Track track()
 * @method static \Ux2Dev\Speedy\Resources\Validation validation()
 */
final class Speedy extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SpeedyManager::class;
    }
}

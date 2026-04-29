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

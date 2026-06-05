<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

enum ShipmentRole: string
{
    case SENDER = 'SENDER';
    case RECIPIENT = 'RECIPIENT';
    case THIRD_PARTY = 'THIRD_PARTY';
}

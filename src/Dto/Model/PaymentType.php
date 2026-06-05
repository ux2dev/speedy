<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

enum PaymentType: string
{
    case CASH = 'CASH';
    case BANK = 'BANK';
}

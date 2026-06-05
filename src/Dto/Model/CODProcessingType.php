<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

enum CODProcessingType: string
{
    case CASH = 'CASH';
    case POSTAL_MONEY_TRANSFER = 'POSTAL_MONEY_TRANSFER';
}

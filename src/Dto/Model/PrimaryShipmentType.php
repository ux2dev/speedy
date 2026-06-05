<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

enum PrimaryShipmentType: string
{
    case RETURN_SHIPMENT = 'RETURN_SHIPMENT';
    case STORAGE_PAYMENT = 'STORAGE_PAYMENT';
    case REDIRECT = 'REDIRECT';
    case SEND_BACK = 'SEND_BACK';
    case MONEY_TRANSFER = 'MONEY_TRANSFER';
    case TRANSPORT_DAMAGED = 'TRANSPORT_DAMAGED';
    case RETURN_VOUCHER = 'RETURN_VOUCHER';
    case SEND_FOR_DESTRUCTION = 'SEND_FOR_DESTRUCTION';
    case SEND_FOR_INSPECTION = 'SEND_FOR_INSPECTION';
    case FORWARD = 'FORWARD';
}

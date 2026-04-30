<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Shipment;

final class FinalizePendingShipmentResponse
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?string $id = null,
        public readonly ?array $parcels = null,
        public readonly ?string $pickupDate = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentPrice $price = null,
        public readonly ?string $deliveryDeadline = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            id: $data['id'] ?? null,
            parcels: isset($data['parcels']) && is_array($data['parcels']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\CreatedShipmentParcel::fromArray($r), $data['parcels']) : null,
            pickupDate: $data['pickupDate'] ?? null,
            price: isset($data['price']) && is_array($data['price']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentPrice::fromArray($data['price']) : null,
            deliveryDeadline: $data['deliveryDeadline'] ?? null,
        );
    }
}
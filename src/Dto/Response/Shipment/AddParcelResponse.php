<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Shipment;

final class AddParcelResponse
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\CreatedShipmentParcel $parcel = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            parcel: isset($data['parcel']) && is_array($data['parcel']) ? \Ux2Dev\Speedy\Dto\Model\CreatedShipmentParcel::fromArray($data['parcel']) : null,
        );
    }
}
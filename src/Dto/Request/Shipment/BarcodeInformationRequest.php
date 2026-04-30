<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Shipment;

final readonly class BarcodeInformationRequest
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentParcelRef $parcel = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->parcel !== null) $out['parcel'] = $this->parcel->toArray();
        return $out;
    }
}
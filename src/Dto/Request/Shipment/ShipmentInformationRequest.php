<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Shipment;

final readonly class ShipmentInformationRequest
{
    public function __construct(
        public readonly ?array $shipmentIds = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->shipmentIds !== null) $out['shipmentIds'] = $this->shipmentIds;
        return $out;
    }
}
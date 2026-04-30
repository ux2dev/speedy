<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Shipment;

final readonly class FinalizePendingShipmentRequest
{
    public function __construct(
        public readonly ?string $shipmentId = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->shipmentId !== null) $out['shipmentId'] = $this->shipmentId;
        return $out;
    }
}
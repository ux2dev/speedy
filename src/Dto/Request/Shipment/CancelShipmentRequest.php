<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Shipment;

final readonly class CancelShipmentRequest
{
    public function __construct(
        public readonly ?string $shipmentId = null,
        public readonly ?string $comment = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->shipmentId !== null) $out['shipmentId'] = $this->shipmentId;
        if ($this->comment !== null) $out['comment'] = $this->comment;
        return $out;
    }
}
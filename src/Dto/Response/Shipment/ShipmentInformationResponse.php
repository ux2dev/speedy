<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Shipment;

final class ShipmentInformationResponse
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?array $shipments = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            shipments: isset($data['shipments']) && is_array($data['shipments']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\Shipment::fromArray($r), $data['shipments']) : null,
        );
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Shipment;

final class BarcodeInformationResponse
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\LabelInfo $labelInfo = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\PrimaryShipment $primaryShipment = null,
        public readonly ?string $primaryParcelId = null,
        public readonly ?string $returnShipmentId = null,
        public readonly ?string $returnParcelId = null,
        public readonly ?string $redirectShipmentId = null,
        public readonly ?string $redirectParcelId = null,
        public readonly ?string $initialShipmentId = null,
        public readonly ?string $initialParcelId = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            labelInfo: isset($data['labelInfo']) && is_array($data['labelInfo']) ? \Ux2Dev\Speedy\Dto\Model\LabelInfo::fromArray($data['labelInfo']) : null,
            primaryShipment: isset($data['primaryShipment']) && is_array($data['primaryShipment']) ? \Ux2Dev\Speedy\Dto\Model\PrimaryShipment::fromArray($data['primaryShipment']) : null,
            primaryParcelId: $data['primaryParcelId'] ?? null,
            returnShipmentId: $data['returnShipmentId'] ?? null,
            returnParcelId: $data['returnParcelId'] ?? null,
            redirectShipmentId: $data['redirectShipmentId'] ?? null,
            redirectParcelId: $data['redirectParcelId'] ?? null,
            initialShipmentId: $data['initialShipmentId'] ?? null,
            initialParcelId: $data['initialParcelId'] ?? null,
        );
    }
}
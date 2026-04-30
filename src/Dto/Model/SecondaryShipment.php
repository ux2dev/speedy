<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class SecondaryShipment
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\PrimaryShipmentType $type = null,
        public readonly ?array $parcels = null,
        public readonly ?string $pickupDate = null,
        public readonly ?int $serviceId = null,
        public readonly ?bool $hasScans = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            type: isset($data['type']) && is_array($data['type']) ? \Ux2Dev\Speedy\Dto\Model\PrimaryShipmentType::fromArray($data['type']) : null,
            parcels: isset($data['parcels']) && is_array($data['parcels']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\ShipmentParcelNumber::fromArray($r), $data['parcels']) : null,
            pickupDate: $data['pickupDate'] ?? null,
            serviceId: $data['serviceId'] ?? null,
            hasScans: $data['hasScans'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->type !== null) $out['type'] = $this->type->toArray();
        if ($this->parcels !== null) $out['parcels'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\ShipmentParcelNumber $x) => $x->toArray(), $this->parcels);
        if ($this->pickupDate !== null) $out['pickupDate'] = $this->pickupDate;
        if ($this->serviceId !== null) $out['serviceId'] = $this->serviceId;
        if ($this->hasScans !== null) $out['hasScans'] = $this->hasScans;
        return $out;
    }
}
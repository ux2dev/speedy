<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class PickupOrder
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?array $shipmentIds = null,
        public readonly ?string $pickupPeriodFrom = null,
        public readonly ?string $pickupPeriodTo = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            shipmentIds: isset($data['shipmentIds']) && is_array($data['shipmentIds']) ? $data['shipmentIds'] : null,
            pickupPeriodFrom: $data['pickupPeriodFrom'] ?? null,
            pickupPeriodTo: $data['pickupPeriodTo'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->shipmentIds !== null) $out['shipmentIds'] = $this->shipmentIds;
        if ($this->pickupPeriodFrom !== null) $out['pickupPeriodFrom'] = $this->pickupPeriodFrom;
        if ($this->pickupPeriodTo !== null) $out['pickupPeriodTo'] = $this->pickupPeriodTo;
        return $out;
    }
}
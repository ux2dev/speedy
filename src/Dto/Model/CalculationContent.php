<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class CalculationContent
{
    public function __construct(
        public readonly ?int $parcelsCount = null,
        public readonly ?float $totalWeight = null,
        public readonly ?bool $documents = null,
        public readonly ?bool $palletized = null,
        public readonly ?array $parcels = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            parcelsCount: $data['parcelsCount'] ?? null,
            totalWeight: $data['totalWeight'] ?? null,
            documents: $data['documents'] ?? null,
            palletized: $data['palletized'] ?? null,
            parcels: isset($data['parcels']) && is_array($data['parcels']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\ShipmentParcel::fromArray($r), $data['parcels']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->parcelsCount !== null) $out['parcelsCount'] = $this->parcelsCount;
        if ($this->totalWeight !== null) $out['totalWeight'] = $this->totalWeight;
        if ($this->documents !== null) $out['documents'] = $this->documents;
        if ($this->palletized !== null) $out['palletized'] = $this->palletized;
        if ($this->parcels !== null) $out['parcels'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\ShipmentParcel $x) => $x->toArray(), $this->parcels);
        return $out;
    }
}
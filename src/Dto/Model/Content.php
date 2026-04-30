<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class Content
{
    public function __construct(
        public readonly ?int $parcelsCount = null,
        public readonly ?float $declaredWeight = null,
        public readonly ?float $measuredWeight = null,
        public readonly ?float $calculationWeight = null,
        public readonly ?string $contents = null,
        public readonly ?bool $documents = null,
        public readonly ?bool $palletized = null,
        public readonly ?array $parcels = null,
        public readonly ?bool $pendingParcels = null,
        public readonly ?string $package = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            parcelsCount: $data['parcelsCount'] ?? null,
            declaredWeight: $data['declaredWeight'] ?? null,
            measuredWeight: $data['measuredWeight'] ?? null,
            calculationWeight: $data['calculationWeight'] ?? null,
            contents: $data['contents'] ?? null,
            documents: $data['documents'] ?? null,
            palletized: $data['palletized'] ?? null,
            parcels: isset($data['parcels']) && is_array($data['parcels']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\Parcel::fromArray($r), $data['parcels']) : null,
            pendingParcels: $data['pendingParcels'] ?? null,
            package: $data['package'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->parcelsCount !== null) $out['parcelsCount'] = $this->parcelsCount;
        if ($this->declaredWeight !== null) $out['declaredWeight'] = $this->declaredWeight;
        if ($this->measuredWeight !== null) $out['measuredWeight'] = $this->measuredWeight;
        if ($this->calculationWeight !== null) $out['calculationWeight'] = $this->calculationWeight;
        if ($this->contents !== null) $out['contents'] = $this->contents;
        if ($this->documents !== null) $out['documents'] = $this->documents;
        if ($this->palletized !== null) $out['palletized'] = $this->palletized;
        if ($this->parcels !== null) $out['parcels'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\Parcel $x) => $x->toArray(), $this->parcels);
        if ($this->pendingParcels !== null) $out['pendingParcels'] = $this->pendingParcels;
        if ($this->package !== null) $out['package'] = $this->package;
        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Location;

final readonly class FindNearestOfficesRequest
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentAddress $address = null,
        public readonly ?int $distance = null,
        public readonly ?int $limit = null,
        public readonly ?string $officeType = null,
        public readonly ?array $officeFeatures = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->address !== null) $out['address'] = $this->address->toArray();
        if ($this->distance !== null) $out['distance'] = $this->distance;
        if ($this->limit !== null) $out['limit'] = $this->limit;
        if ($this->officeType !== null) $out['officeType'] = $this->officeType;
        if ($this->officeFeatures !== null) $out['officeFeatures'] = $this->officeFeatures;
        return $out;
    }
}
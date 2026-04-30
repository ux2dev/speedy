<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class Site
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?int $countryId = null,
        public readonly ?int $mainSiteId = null,
        public readonly ?string $type = null,
        public readonly ?string $typeEn = null,
        public readonly ?string $name = null,
        public readonly ?string $nameEn = null,
        public readonly ?string $municipality = null,
        public readonly ?string $municipalityEn = null,
        public readonly ?string $region = null,
        public readonly ?string $regionEn = null,
        public readonly ?string $postCode = null,
        public readonly ?string $servingDays = null,
        public readonly ?int $addressNomenclature = null,
        public readonly ?float $x = null,
        public readonly ?float $y = null,
        public readonly ?int $servingOfficeId = null,
        public readonly ?int $servingHubOfficeId = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            countryId: $data['countryId'] ?? null,
            mainSiteId: $data['mainSiteId'] ?? null,
            type: $data['type'] ?? null,
            typeEn: $data['typeEn'] ?? null,
            name: $data['name'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            municipality: $data['municipality'] ?? null,
            municipalityEn: $data['municipalityEn'] ?? null,
            region: $data['region'] ?? null,
            regionEn: $data['regionEn'] ?? null,
            postCode: $data['postCode'] ?? null,
            servingDays: $data['servingDays'] ?? null,
            addressNomenclature: $data['addressNomenclature'] ?? null,
            x: $data['x'] ?? null,
            y: $data['y'] ?? null,
            servingOfficeId: $data['servingOfficeId'] ?? null,
            servingHubOfficeId: $data['servingHubOfficeId'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->countryId !== null) $out['countryId'] = $this->countryId;
        if ($this->mainSiteId !== null) $out['mainSiteId'] = $this->mainSiteId;
        if ($this->type !== null) $out['type'] = $this->type;
        if ($this->typeEn !== null) $out['typeEn'] = $this->typeEn;
        if ($this->name !== null) $out['name'] = $this->name;
        if ($this->nameEn !== null) $out['nameEn'] = $this->nameEn;
        if ($this->municipality !== null) $out['municipality'] = $this->municipality;
        if ($this->municipalityEn !== null) $out['municipalityEn'] = $this->municipalityEn;
        if ($this->region !== null) $out['region'] = $this->region;
        if ($this->regionEn !== null) $out['regionEn'] = $this->regionEn;
        if ($this->postCode !== null) $out['postCode'] = $this->postCode;
        if ($this->servingDays !== null) $out['servingDays'] = $this->servingDays;
        if ($this->addressNomenclature !== null) $out['addressNomenclature'] = $this->addressNomenclature;
        if ($this->x !== null) $out['x'] = $this->x;
        if ($this->y !== null) $out['y'] = $this->y;
        if ($this->servingOfficeId !== null) $out['servingOfficeId'] = $this->servingOfficeId;
        if ($this->servingHubOfficeId !== null) $out['servingHubOfficeId'] = $this->servingHubOfficeId;
        return $out;
    }
}
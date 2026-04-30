<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class Country
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?string $name = null,
        public readonly ?string $nameEn = null,
        public readonly ?string $isoAlpha2 = null,
        public readonly ?string $isoAlpha3 = null,
        public readonly ?string $currencyCode = null,
        public readonly ?bool $requireState = null,
        public readonly ?int $addressType = null,
        public readonly ?int $defaultOfficeId = null,
        public readonly ?int $siteNomen = null,
        public readonly ?array $streetTypes = null,
        public readonly ?array $complexTypes = null,
        public readonly ?array $postCodeFormats = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            isoAlpha2: $data['isoAlpha2'] ?? null,
            isoAlpha3: $data['isoAlpha3'] ?? null,
            currencyCode: $data['currencyCode'] ?? null,
            requireState: $data['requireState'] ?? null,
            addressType: $data['addressType'] ?? null,
            defaultOfficeId: $data['defaultOfficeId'] ?? null,
            siteNomen: $data['siteNomen'] ?? null,
            streetTypes: isset($data['streetTypes']) && is_array($data['streetTypes']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\AddressNomenclatureType::fromArray($r), $data['streetTypes']) : null,
            complexTypes: isset($data['complexTypes']) && is_array($data['complexTypes']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\AddressNomenclatureType::fromArray($r), $data['complexTypes']) : null,
            postCodeFormats: isset($data['postCodeFormats']) && is_array($data['postCodeFormats']) ? $data['postCodeFormats'] : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->name !== null) $out['name'] = $this->name;
        if ($this->nameEn !== null) $out['nameEn'] = $this->nameEn;
        if ($this->isoAlpha2 !== null) $out['isoAlpha2'] = $this->isoAlpha2;
        if ($this->isoAlpha3 !== null) $out['isoAlpha3'] = $this->isoAlpha3;
        if ($this->currencyCode !== null) $out['currencyCode'] = $this->currencyCode;
        if ($this->requireState !== null) $out['requireState'] = $this->requireState;
        if ($this->addressType !== null) $out['addressType'] = $this->addressType;
        if ($this->defaultOfficeId !== null) $out['defaultOfficeId'] = $this->defaultOfficeId;
        if ($this->siteNomen !== null) $out['siteNomen'] = $this->siteNomen;
        if ($this->streetTypes !== null) $out['streetTypes'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\AddressNomenclatureType $x) => $x->toArray(), $this->streetTypes);
        if ($this->complexTypes !== null) $out['complexTypes'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\AddressNomenclatureType $x) => $x->toArray(), $this->complexTypes);
        if ($this->postCodeFormats !== null) $out['postCodeFormats'] = $this->postCodeFormats;
        return $out;
    }
}
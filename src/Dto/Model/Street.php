<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class Street
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?int $actualId = null,
        public readonly ?int $siteId = null,
        public readonly ?string $type = null,
        public readonly ?string $typeEn = null,
        public readonly ?string $name = null,
        public readonly ?string $nameEn = null,
        public readonly ?string $actualType = null,
        public readonly ?string $actualTypeEn = null,
        public readonly ?string $actualName = null,
        public readonly ?string $actualNameEn = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            actualId: $data['actualId'] ?? null,
            siteId: $data['siteId'] ?? null,
            type: $data['type'] ?? null,
            typeEn: $data['typeEn'] ?? null,
            name: $data['name'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            actualType: $data['actualType'] ?? null,
            actualTypeEn: $data['actualTypeEn'] ?? null,
            actualName: $data['actualName'] ?? null,
            actualNameEn: $data['actualNameEn'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->actualId !== null) $out['actualId'] = $this->actualId;
        if ($this->siteId !== null) $out['siteId'] = $this->siteId;
        if ($this->type !== null) $out['type'] = $this->type;
        if ($this->typeEn !== null) $out['typeEn'] = $this->typeEn;
        if ($this->name !== null) $out['name'] = $this->name;
        if ($this->nameEn !== null) $out['nameEn'] = $this->nameEn;
        if ($this->actualType !== null) $out['actualType'] = $this->actualType;
        if ($this->actualTypeEn !== null) $out['actualTypeEn'] = $this->actualTypeEn;
        if ($this->actualName !== null) $out['actualName'] = $this->actualName;
        if ($this->actualNameEn !== null) $out['actualNameEn'] = $this->actualNameEn;
        return $out;
    }
}
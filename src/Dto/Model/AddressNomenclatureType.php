<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class AddressNomenclatureType
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $nameEn = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            nameEn: $data['nameEn'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->name !== null) $out['name'] = $this->name;
        if ($this->nameEn !== null) $out['nameEn'] = $this->nameEn;
        return $out;
    }
}
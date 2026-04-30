<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class GoodsItem
{
    public function __construct(
        public readonly ?string $description = null,
        public readonly ?string $hsCode = null,
        public readonly ?int $quantity = null,
        public readonly ?float $valuePerItem = null,
        public readonly ?float $weightPerItem = null,
        public readonly ?string $originCountryCode = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            description: $data['description'] ?? null,
            hsCode: $data['hsCode'] ?? null,
            quantity: $data['quantity'] ?? null,
            valuePerItem: $data['valuePerItem'] ?? null,
            weightPerItem: $data['weightPerItem'] ?? null,
            originCountryCode: $data['originCountryCode'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->description !== null) $out['description'] = $this->description;
        if ($this->hsCode !== null) $out['hsCode'] = $this->hsCode;
        if ($this->quantity !== null) $out['quantity'] = $this->quantity;
        if ($this->valuePerItem !== null) $out['valuePerItem'] = $this->valuePerItem;
        if ($this->weightPerItem !== null) $out['weightPerItem'] = $this->weightPerItem;
        if ($this->originCountryCode !== null) $out['originCountryCode'] = $this->originCountryCode;
        return $out;
    }
}
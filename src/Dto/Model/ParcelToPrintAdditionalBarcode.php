<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ParcelToPrintAdditionalBarcode
{
    public function __construct(
        public readonly ?string $valueType = null,
        public readonly ?string $value = null,
        public readonly ?string $label = null,
        public readonly ?string $format = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            valueType: $data['valueType'] ?? null,
            value: $data['value'] ?? null,
            label: $data['label'] ?? null,
            format: $data['format'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->valueType !== null) $out['valueType'] = $this->valueType;
        if ($this->value !== null) $out['value'] = $this->value;
        if ($this->label !== null) $out['label'] = $this->label;
        if ($this->format !== null) $out['format'] = $this->format;
        return $out;
    }
}
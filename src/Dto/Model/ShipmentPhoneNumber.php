<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentPhoneNumber
{
    public function __construct(
        public readonly ?string $number = null,
        public readonly ?string $extension = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            number: $data['number'] ?? null,
            extension: $data['extension'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->number !== null) $out['number'] = $this->number;
        if ($this->extension !== null) $out['extension'] = $this->extension;
        return $out;
    }
}
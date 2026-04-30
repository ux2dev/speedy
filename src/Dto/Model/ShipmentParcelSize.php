<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentParcelSize
{
    public function __construct(
        public readonly ?int $width = null,
        public readonly ?int $height = null,
        public readonly ?int $depth = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            width: $data['width'] ?? null,
            height: $data['height'] ?? null,
            depth: $data['depth'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->width !== null) $out['width'] = $this->width;
        if ($this->height !== null) $out['height'] = $this->height;
        if ($this->depth !== null) $out['depth'] = $this->depth;
        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class PrimaryShipment
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\PrimaryShipmentType $type = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            type: isset($data['type']) && is_string($data['type']) ? \Ux2Dev\Speedy\Dto\Model\PrimaryShipmentType::tryFrom($data['type']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->type !== null) $out['type'] = $this->type->value;
        return $out;
    }
}
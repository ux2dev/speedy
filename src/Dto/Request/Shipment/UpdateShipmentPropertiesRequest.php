<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Shipment;

final readonly class UpdateShipmentPropertiesRequest
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly mixed $properties = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->properties !== null) $out['properties'] = $this->properties;
        return $out;
    }
}
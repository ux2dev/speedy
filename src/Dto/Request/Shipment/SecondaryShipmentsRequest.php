<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Shipment;

final readonly class SecondaryShipmentsRequest
{
    public function __construct(
        public readonly ?array $types = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->types !== null) $out['types'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\PrimaryShipmentType $x) => $x->value, $this->types);
        return $out;
    }
}
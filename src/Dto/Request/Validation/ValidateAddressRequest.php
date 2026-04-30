<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Validation;

final readonly class ValidateAddressRequest
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentAddress $address = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->address !== null) $out['address'] = $this->address->toArray();
        return $out;
    }
}
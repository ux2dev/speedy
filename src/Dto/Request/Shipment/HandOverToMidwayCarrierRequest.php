<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Shipment;

final readonly class HandOverToMidwayCarrierRequest
{
    public function __construct(
        public readonly ?array $parcels = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->parcels !== null) $out['parcels'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\MidwayCarrierParcelHandOver $x) => $x->toArray(), $this->parcels);
        return $out;
    }
}
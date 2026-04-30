<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Track;

final readonly class TrackRequest
{
    public function __construct(
        public readonly ?array $parcels = null,
        public readonly ?bool $lastOperationOnly = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->parcels !== null) $out['parcels'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\TrackShipmentParcelRef $x) => $x->toArray(), $this->parcels);
        if ($this->lastOperationOnly !== null) $out['lastOperationOnly'] = $this->lastOperationOnly;
        return $out;
    }
}
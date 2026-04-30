<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Location;

final readonly class FindCountryRequest
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $isoAlpha2 = null,
        public readonly ?string $isoAlpha3 = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->name !== null) $out['name'] = $this->name;
        if ($this->isoAlpha2 !== null) $out['isoAlpha2'] = $this->isoAlpha2;
        if ($this->isoAlpha3 !== null) $out['isoAlpha3'] = $this->isoAlpha3;
        return $out;
    }
}
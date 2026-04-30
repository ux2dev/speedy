<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Location;

final readonly class FindSiteRequest
{
    public function __construct(
        public readonly ?int $countryId = null,
        public readonly ?string $name = null,
        public readonly ?string $postCode = null,
        public readonly ?string $type = null,
        public readonly ?string $municipality = null,
        public readonly ?string $region = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->countryId !== null) $out['countryId'] = $this->countryId;
        if ($this->name !== null) $out['name'] = $this->name;
        if ($this->postCode !== null) $out['postCode'] = $this->postCode;
        if ($this->type !== null) $out['type'] = $this->type;
        if ($this->municipality !== null) $out['municipality'] = $this->municipality;
        if ($this->region !== null) $out['region'] = $this->region;
        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Location;

final readonly class FindOfficeRequest
{
    public function __construct(
        public readonly ?int $countryId = null,
        public readonly ?int $siteId = null,
        public readonly ?string $siteName = null,
        public readonly ?string $name = null,
        public readonly ?int $limit = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->countryId !== null) $out['countryId'] = $this->countryId;
        if ($this->siteId !== null) $out['siteId'] = $this->siteId;
        if ($this->siteName !== null) $out['siteName'] = $this->siteName;
        if ($this->name !== null) $out['name'] = $this->name;
        if ($this->limit !== null) $out['limit'] = $this->limit;
        return $out;
    }
}
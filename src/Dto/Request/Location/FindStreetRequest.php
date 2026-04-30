<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Location;

final readonly class FindStreetRequest
{
    public function __construct(
        public readonly ?int $siteId = null,
        public readonly ?string $name = null,
        public readonly ?string $type = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->siteId !== null) $out['siteId'] = $this->siteId;
        if ($this->name !== null) $out['name'] = $this->name;
        if ($this->type !== null) $out['type'] = $this->type;
        return $out;
    }
}
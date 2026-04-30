<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Location;

final readonly class SearchAddressRequest
{
    public function __construct(
        public readonly ?int $siteId = null,
        public readonly ?int $complexId = null,
        public readonly ?int $streetId = null,
        public readonly ?int $poiId = null,
        public readonly ?string $blockNo = null,
        public readonly ?string $streetNo = null,
        public readonly ?string $entranceNo = null,
        public readonly ?bool $returnSiteCenterIfNoAddress = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->siteId !== null) $out['siteId'] = $this->siteId;
        if ($this->complexId !== null) $out['complexId'] = $this->complexId;
        if ($this->streetId !== null) $out['streetId'] = $this->streetId;
        if ($this->poiId !== null) $out['poiId'] = $this->poiId;
        if ($this->blockNo !== null) $out['blockNo'] = $this->blockNo;
        if ($this->streetNo !== null) $out['streetNo'] = $this->streetNo;
        if ($this->entranceNo !== null) $out['entranceNo'] = $this->entranceNo;
        if ($this->returnSiteCenterIfNoAddress !== null) $out['returnSiteCenterIfNoAddress'] = $this->returnSiteCenterIfNoAddress;
        return $out;
    }
}
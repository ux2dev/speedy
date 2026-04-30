<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class AddressLocation
{
    public function __construct(
        public readonly ?int $countryId = null,
        public readonly ?string $stateId = null,
        public readonly ?int $siteId = null,
        public readonly ?string $siteType = null,
        public readonly ?string $siteName = null,
        public readonly ?string $postCode = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            countryId: $data['countryId'] ?? null,
            stateId: $data['stateId'] ?? null,
            siteId: $data['siteId'] ?? null,
            siteType: $data['siteType'] ?? null,
            siteName: $data['siteName'] ?? null,
            postCode: $data['postCode'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->countryId !== null) $out['countryId'] = $this->countryId;
        if ($this->stateId !== null) $out['stateId'] = $this->stateId;
        if ($this->siteId !== null) $out['siteId'] = $this->siteId;
        if ($this->siteType !== null) $out['siteType'] = $this->siteType;
        if ($this->siteName !== null) $out['siteName'] = $this->siteName;
        if ($this->postCode !== null) $out['postCode'] = $this->postCode;
        return $out;
    }
}
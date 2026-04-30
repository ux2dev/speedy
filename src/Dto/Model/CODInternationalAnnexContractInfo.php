<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class CODInternationalAnnexContractInfo
{
    public function __construct(
        public readonly ?int $countryId = null,
        public readonly ?string $countryName = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            countryId: $data['countryId'] ?? null,
            countryName: $data['countryName'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->countryId !== null) $out['countryId'] = $this->countryId;
        if ($this->countryName !== null) $out['countryName'] = $this->countryName;
        return $out;
    }
}
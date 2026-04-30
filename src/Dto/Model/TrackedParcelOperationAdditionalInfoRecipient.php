<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class TrackedParcelOperationAdditionalInfoRecipient
{
    public function __construct(
        public readonly ?int $countryId = null,
        public readonly ?string $phone1 = null,
        public readonly ?string $phone2 = null,
        public readonly ?string $phone3 = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            countryId: $data['countryId'] ?? null,
            phone1: $data['phone1'] ?? null,
            phone2: $data['phone2'] ?? null,
            phone3: $data['phone3'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->countryId !== null) $out['countryId'] = $this->countryId;
        if ($this->phone1 !== null) $out['phone1'] = $this->phone1;
        if ($this->phone2 !== null) $out['phone2'] = $this->phone2;
        if ($this->phone3 !== null) $out['phone3'] = $this->phone3;
        return $out;
    }
}
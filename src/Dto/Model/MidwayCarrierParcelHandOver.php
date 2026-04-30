<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class MidwayCarrierParcelHandOver
{
    public function __construct(
        public readonly ?string $siteName = null,
        public readonly ?string $id = null,
        public readonly ?int $dateTime = null,
        public readonly ?int $countryId = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            siteName: $data['siteName'] ?? null,
            id: $data['id'] ?? null,
            dateTime: $data['dateTime'] ?? null,
            countryId: $data['countryId'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->siteName !== null) $out['siteName'] = $this->siteName;
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->dateTime !== null) $out['dateTime'] = $this->dateTime;
        if ($this->countryId !== null) $out['countryId'] = $this->countryId;
        return $out;
    }
}
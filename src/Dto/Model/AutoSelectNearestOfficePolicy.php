<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class AutoSelectNearestOfficePolicy
{
    public function __construct(
        public readonly ?string $unavailableNearestOfficeAction = null,
        public readonly ?string $officeType = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            unavailableNearestOfficeAction: $data['unavailableNearestOfficeAction'] ?? null,
            officeType: $data['officeType'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->unavailableNearestOfficeAction !== null) $out['unavailableNearestOfficeAction'] = $this->unavailableNearestOfficeAction;
        if ($this->officeType !== null) $out['officeType'] = $this->officeType;
        return $out;
    }
}
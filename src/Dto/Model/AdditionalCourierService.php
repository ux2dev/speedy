<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class AdditionalCourierService
{
    public function __construct(
        public readonly ?string $allowance = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            allowance: $data['allowance'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->allowance !== null) $out['allowance'] = $this->allowance;
        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentDeclaredValueAdditionalService
{
    public function __construct(
        public readonly ?float $amount = null,
        public readonly ?bool $fragile = null,
        public readonly ?bool $ignoreIfNotApplicable = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            amount: $data['amount'] ?? null,
            fragile: $data['fragile'] ?? null,
            ignoreIfNotApplicable: $data['ignoreIfNotApplicable'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->amount !== null) $out['amount'] = $this->amount;
        if ($this->fragile !== null) $out['fragile'] = $this->fragile;
        if ($this->ignoreIfNotApplicable !== null) $out['ignoreIfNotApplicable'] = $this->ignoreIfNotApplicable;
        return $out;
    }
}
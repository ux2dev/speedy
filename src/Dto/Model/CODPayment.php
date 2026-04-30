<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class CODPayment
{
    public function __construct(
        public readonly ?string $date = null,
        public readonly ?float $totalPayedOutAmount = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            date: $data['date'] ?? null,
            totalPayedOutAmount: $data['totalPayedOutAmount'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->date !== null) $out['date'] = $this->date;
        if ($this->totalPayedOutAmount !== null) $out['totalPayedOutAmount'] = $this->totalPayedOutAmount;
        return $out;
    }
}
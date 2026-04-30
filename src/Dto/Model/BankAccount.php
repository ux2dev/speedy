<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class BankAccount
{
    public function __construct(
        public readonly ?string $iban = null,
        public readonly ?string $accountHolder = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            iban: $data['iban'] ?? null,
            accountHolder: $data['accountHolder'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->iban !== null) $out['iban'] = $this->iban;
        if ($this->accountHolder !== null) $out['accountHolder'] = $this->accountHolder;
        return $out;
    }
}
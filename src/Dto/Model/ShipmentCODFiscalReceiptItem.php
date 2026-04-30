<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentCODFiscalReceiptItem
{
    public function __construct(
        public readonly ?string $description = null,
        public readonly ?string $vatGroup = null,
        public readonly ?float $amount = null,
        public readonly ?float $amountWithVat = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            description: $data['description'] ?? null,
            vatGroup: $data['vatGroup'] ?? null,
            amount: $data['amount'] ?? null,
            amountWithVat: $data['amountWithVat'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->description !== null) $out['description'] = $this->description;
        if ($this->vatGroup !== null) $out['vatGroup'] = $this->vatGroup;
        if ($this->amount !== null) $out['amount'] = $this->amount;
        if ($this->amountWithVat !== null) $out['amountWithVat'] = $this->amountWithVat;
        return $out;
    }
}
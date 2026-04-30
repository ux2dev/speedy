<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class MoneyTransferPremium
{
    public function __construct(
        public readonly ?float $amount = null,
        public readonly ?float $amountLocal = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentRole $payer = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            amount: $data['amount'] ?? null,
            amountLocal: $data['amountLocal'] ?? null,
            payer: isset($data['payer']) && is_array($data['payer']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentRole::fromArray($data['payer']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->amount !== null) $out['amount'] = $this->amount;
        if ($this->amountLocal !== null) $out['amountLocal'] = $this->amountLocal;
        if ($this->payer !== null) $out['payer'] = $this->payer->toArray();
        return $out;
    }
}
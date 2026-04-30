<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentPrice
{
    public function __construct(
        public readonly ?float $amount = null,
        public readonly ?float $vat = null,
        public readonly ?float $total = null,
        public readonly ?string $currency = null,
        public readonly mixed $details = null,
        public readonly ?float $amountLocal = null,
        public readonly ?float $vatLocal = null,
        public readonly ?float $totalLocal = null,
        public readonly ?string $currencyLocal = null,
        public readonly mixed $detailsLocal = null,
        public readonly ?int $currencyExchangeRateUnit = null,
        public readonly ?float $currencyExchangeRate = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ReturnAmounts $returnAmounts = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            amount: $data['amount'] ?? null,
            vat: $data['vat'] ?? null,
            total: $data['total'] ?? null,
            currency: $data['currency'] ?? null,
            details: $data['details'] ?? null,
            amountLocal: $data['amountLocal'] ?? null,
            vatLocal: $data['vatLocal'] ?? null,
            totalLocal: $data['totalLocal'] ?? null,
            currencyLocal: $data['currencyLocal'] ?? null,
            detailsLocal: $data['detailsLocal'] ?? null,
            currencyExchangeRateUnit: $data['currencyExchangeRateUnit'] ?? null,
            currencyExchangeRate: $data['currencyExchangeRate'] ?? null,
            returnAmounts: isset($data['returnAmounts']) && is_array($data['returnAmounts']) ? \Ux2Dev\Speedy\Dto\Model\ReturnAmounts::fromArray($data['returnAmounts']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->amount !== null) $out['amount'] = $this->amount;
        if ($this->vat !== null) $out['vat'] = $this->vat;
        if ($this->total !== null) $out['total'] = $this->total;
        if ($this->currency !== null) $out['currency'] = $this->currency;
        if ($this->details !== null) $out['details'] = $this->details;
        if ($this->amountLocal !== null) $out['amountLocal'] = $this->amountLocal;
        if ($this->vatLocal !== null) $out['vatLocal'] = $this->vatLocal;
        if ($this->totalLocal !== null) $out['totalLocal'] = $this->totalLocal;
        if ($this->currencyLocal !== null) $out['currencyLocal'] = $this->currencyLocal;
        if ($this->detailsLocal !== null) $out['detailsLocal'] = $this->detailsLocal;
        if ($this->currencyExchangeRateUnit !== null) $out['currencyExchangeRateUnit'] = $this->currencyExchangeRateUnit;
        if ($this->currencyExchangeRate !== null) $out['currencyExchangeRate'] = $this->currencyExchangeRate;
        if ($this->returnAmounts !== null) $out['returnAmounts'] = $this->returnAmounts->toArray();
        return $out;
    }
}
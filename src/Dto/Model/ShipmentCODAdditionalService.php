<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentCODAdditionalService
{
    public function __construct(
        public readonly ?float $amount = null,
        public readonly ?string $currencyCode = null,
        public readonly ?bool $payoutToThirdParty = null,
        public readonly ?bool $payoutToLoggedClient = null,
        public readonly ?string $processingType = null,
        public readonly ?bool $includeShippingPrice = null,
        public readonly ?bool $cardPaymentForbidden = null,
        public readonly ?array $fiscalReceiptItems = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            amount: $data['amount'] ?? null,
            currencyCode: $data['currencyCode'] ?? null,
            payoutToThirdParty: $data['payoutToThirdParty'] ?? null,
            payoutToLoggedClient: $data['payoutToLoggedClient'] ?? null,
            processingType: $data['processingType'] ?? null,
            includeShippingPrice: $data['includeShippingPrice'] ?? null,
            cardPaymentForbidden: $data['cardPaymentForbidden'] ?? null,
            fiscalReceiptItems: isset($data['fiscalReceiptItems']) && is_array($data['fiscalReceiptItems']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\ShipmentCODFiscalReceiptItem::fromArray($r), $data['fiscalReceiptItems']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->amount !== null) $out['amount'] = $this->amount;
        if ($this->currencyCode !== null) $out['currencyCode'] = $this->currencyCode;
        if ($this->payoutToThirdParty !== null) $out['payoutToThirdParty'] = $this->payoutToThirdParty;
        if ($this->payoutToLoggedClient !== null) $out['payoutToLoggedClient'] = $this->payoutToLoggedClient;
        if ($this->processingType !== null) $out['processingType'] = $this->processingType;
        if ($this->includeShippingPrice !== null) $out['includeShippingPrice'] = $this->includeShippingPrice;
        if ($this->cardPaymentForbidden !== null) $out['cardPaymentForbidden'] = $this->cardPaymentForbidden;
        if ($this->fiscalReceiptItems !== null) $out['fiscalReceiptItems'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\ShipmentCODFiscalReceiptItem $x) => $x->toArray(), $this->fiscalReceiptItems);
        return $out;
    }
}
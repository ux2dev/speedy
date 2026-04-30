<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentContent
{
    public function __construct(
        public readonly ?int $parcelsCount = null,
        public readonly ?float $totalWeight = null,
        public readonly ?string $contents = null,
        public readonly ?string $package = null,
        public readonly ?bool $documents = null,
        public readonly ?bool $palletized = null,
        public readonly ?array $parcels = null,
        public readonly ?bool $pendingParcels = null,
        public readonly ?bool $exciseGoods = null,
        public readonly ?bool $lq = null,
        public readonly ?string $uitCode = null,
        public readonly ?float $goodsValue = null,
        public readonly ?string $goodsValueCurrencyCode = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ParcelToPrintAdditionalBarcode $printAdditionalBarcode = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            parcelsCount: $data['parcelsCount'] ?? null,
            totalWeight: $data['totalWeight'] ?? null,
            contents: $data['contents'] ?? null,
            package: $data['package'] ?? null,
            documents: $data['documents'] ?? null,
            palletized: $data['palletized'] ?? null,
            parcels: isset($data['parcels']) && is_array($data['parcels']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\ShipmentParcel::fromArray($r), $data['parcels']) : null,
            pendingParcels: $data['pendingParcels'] ?? null,
            exciseGoods: $data['exciseGoods'] ?? null,
            lq: $data['lq'] ?? null,
            uitCode: $data['uitCode'] ?? null,
            goodsValue: $data['goodsValue'] ?? null,
            goodsValueCurrencyCode: $data['goodsValueCurrencyCode'] ?? null,
            printAdditionalBarcode: isset($data['printAdditionalBarcode']) && is_array($data['printAdditionalBarcode']) ? \Ux2Dev\Speedy\Dto\Model\ParcelToPrintAdditionalBarcode::fromArray($data['printAdditionalBarcode']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->parcelsCount !== null) $out['parcelsCount'] = $this->parcelsCount;
        if ($this->totalWeight !== null) $out['totalWeight'] = $this->totalWeight;
        if ($this->contents !== null) $out['contents'] = $this->contents;
        if ($this->package !== null) $out['package'] = $this->package;
        if ($this->documents !== null) $out['documents'] = $this->documents;
        if ($this->palletized !== null) $out['palletized'] = $this->palletized;
        if ($this->parcels !== null) $out['parcels'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\ShipmentParcel $x) => $x->toArray(), $this->parcels);
        if ($this->pendingParcels !== null) $out['pendingParcels'] = $this->pendingParcels;
        if ($this->exciseGoods !== null) $out['exciseGoods'] = $this->exciseGoods;
        if ($this->lq !== null) $out['lq'] = $this->lq;
        if ($this->uitCode !== null) $out['uitCode'] = $this->uitCode;
        if ($this->goodsValue !== null) $out['goodsValue'] = $this->goodsValue;
        if ($this->goodsValueCurrencyCode !== null) $out['goodsValueCurrencyCode'] = $this->goodsValueCurrencyCode;
        if ($this->printAdditionalBarcode !== null) $out['printAdditionalBarcode'] = $this->printAdditionalBarcode->toArray();
        return $out;
    }
}
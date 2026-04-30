<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentParcel
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?int $seqNo = null,
        public readonly ?int $packageUniqueNumber = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentParcelSize $size = null,
        public readonly ?float $weight = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ExternalCarrierParcelNumber $pickupExternalCarrierParcelNumber = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ExternalCarrierParcelNumber $deliveryExternalCarrierParcelNumber = null,
        public readonly ?string $ref1 = null,
        public readonly ?string $ref2 = null,
        public readonly ?array $goods = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ParcelToPrintAdditionalBarcode $printAdditionalBarcode = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            seqNo: $data['seqNo'] ?? null,
            packageUniqueNumber: $data['packageUniqueNumber'] ?? null,
            size: isset($data['size']) && is_array($data['size']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentParcelSize::fromArray($data['size']) : null,
            weight: $data['weight'] ?? null,
            pickupExternalCarrierParcelNumber: isset($data['pickupExternalCarrierParcelNumber']) && is_array($data['pickupExternalCarrierParcelNumber']) ? \Ux2Dev\Speedy\Dto\Model\ExternalCarrierParcelNumber::fromArray($data['pickupExternalCarrierParcelNumber']) : null,
            deliveryExternalCarrierParcelNumber: isset($data['deliveryExternalCarrierParcelNumber']) && is_array($data['deliveryExternalCarrierParcelNumber']) ? \Ux2Dev\Speedy\Dto\Model\ExternalCarrierParcelNumber::fromArray($data['deliveryExternalCarrierParcelNumber']) : null,
            ref1: $data['ref1'] ?? null,
            ref2: $data['ref2'] ?? null,
            goods: isset($data['goods']) && is_array($data['goods']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\GoodsItem::fromArray($r), $data['goods']) : null,
            printAdditionalBarcode: isset($data['printAdditionalBarcode']) && is_array($data['printAdditionalBarcode']) ? \Ux2Dev\Speedy\Dto\Model\ParcelToPrintAdditionalBarcode::fromArray($data['printAdditionalBarcode']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->seqNo !== null) $out['seqNo'] = $this->seqNo;
        if ($this->packageUniqueNumber !== null) $out['packageUniqueNumber'] = $this->packageUniqueNumber;
        if ($this->size !== null) $out['size'] = $this->size->toArray();
        if ($this->weight !== null) $out['weight'] = $this->weight;
        if ($this->pickupExternalCarrierParcelNumber !== null) $out['pickupExternalCarrierParcelNumber'] = $this->pickupExternalCarrierParcelNumber->toArray();
        if ($this->deliveryExternalCarrierParcelNumber !== null) $out['deliveryExternalCarrierParcelNumber'] = $this->deliveryExternalCarrierParcelNumber->toArray();
        if ($this->ref1 !== null) $out['ref1'] = $this->ref1;
        if ($this->ref2 !== null) $out['ref2'] = $this->ref2;
        if ($this->goods !== null) $out['goods'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\GoodsItem $x) => $x->toArray(), $this->goods);
        if ($this->printAdditionalBarcode !== null) $out['printAdditionalBarcode'] = $this->printAdditionalBarcode->toArray();
        return $out;
    }
}
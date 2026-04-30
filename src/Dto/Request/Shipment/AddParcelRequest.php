<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Shipment;

final readonly class AddParcelRequest
{
    public function __construct(
        public readonly ?string $shipmentId = null,
        public readonly ?float $declaredValueAmount = null,
        public readonly ?float $codAmount = null,
        public readonly ?array $codFiscalReceiptItems = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentParcel $parcel = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->shipmentId !== null) $out['shipmentId'] = $this->shipmentId;
        if ($this->declaredValueAmount !== null) $out['declaredValueAmount'] = $this->declaredValueAmount;
        if ($this->codAmount !== null) $out['codAmount'] = $this->codAmount;
        if ($this->codFiscalReceiptItems !== null) $out['codFiscalReceiptItems'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\ShipmentCODFiscalReceiptItem $x) => $x->toArray(), $this->codFiscalReceiptItems);
        if ($this->parcel !== null) $out['parcel'] = $this->parcel->toArray();
        return $out;
    }
}
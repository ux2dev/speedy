<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class PayoutDetails
{
    public function __construct(
        public readonly ?int $lineNo = null,
        public readonly ?string $shipmentId = null,
        public readonly ?string $pickupDate = null,
        public readonly ?string $primaryShipmentPickupDate = null,
        public readonly ?string $deliveryDate = null,
        public readonly ?string $sender = null,
        public readonly ?string $recipient = null,
        public readonly ?string $note = null,
        public readonly ?string $ref1 = null,
        public readonly ?string $ref2 = null,
        public readonly ?string $currency = null,
        public readonly ?int $order = null,
        public readonly ?float $amount = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            lineNo: $data['lineNo'] ?? null,
            shipmentId: $data['shipmentId'] ?? null,
            pickupDate: $data['pickupDate'] ?? null,
            primaryShipmentPickupDate: $data['primaryShipmentPickupDate'] ?? null,
            deliveryDate: $data['deliveryDate'] ?? null,
            sender: $data['sender'] ?? null,
            recipient: $data['recipient'] ?? null,
            note: $data['note'] ?? null,
            ref1: $data['ref1'] ?? null,
            ref2: $data['ref2'] ?? null,
            currency: $data['currency'] ?? null,
            order: $data['order'] ?? null,
            amount: $data['amount'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->lineNo !== null) $out['lineNo'] = $this->lineNo;
        if ($this->shipmentId !== null) $out['shipmentId'] = $this->shipmentId;
        if ($this->pickupDate !== null) $out['pickupDate'] = $this->pickupDate;
        if ($this->primaryShipmentPickupDate !== null) $out['primaryShipmentPickupDate'] = $this->primaryShipmentPickupDate;
        if ($this->deliveryDate !== null) $out['deliveryDate'] = $this->deliveryDate;
        if ($this->sender !== null) $out['sender'] = $this->sender;
        if ($this->recipient !== null) $out['recipient'] = $this->recipient;
        if ($this->note !== null) $out['note'] = $this->note;
        if ($this->ref1 !== null) $out['ref1'] = $this->ref1;
        if ($this->ref2 !== null) $out['ref2'] = $this->ref2;
        if ($this->currency !== null) $out['currency'] = $this->currency;
        if ($this->order !== null) $out['order'] = $this->order;
        if ($this->amount !== null) $out['amount'] = $this->amount;
        return $out;
    }
}
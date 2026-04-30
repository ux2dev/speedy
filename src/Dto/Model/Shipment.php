<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class Shipment
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?string $id = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Sender $sender = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Recipient $recipient = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentService $service = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Content $content = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Payment $payment = null,
        public readonly ?string $shipmentNote = null,
        public readonly ?string $ref1 = null,
        public readonly ?string $ref2 = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentPrice $price = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentDelivery $delivery = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\PrimaryShipment $primaryShipment = null,
        public readonly ?string $returnShipmentId = null,
        public readonly ?string $redirectShipmentId = null,
        public readonly ?bool $pendingShipment = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            id: $data['id'] ?? null,
            sender: isset($data['sender']) && is_array($data['sender']) ? \Ux2Dev\Speedy\Dto\Model\Sender::fromArray($data['sender']) : null,
            recipient: isset($data['recipient']) && is_array($data['recipient']) ? \Ux2Dev\Speedy\Dto\Model\Recipient::fromArray($data['recipient']) : null,
            service: isset($data['service']) && is_array($data['service']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentService::fromArray($data['service']) : null,
            content: isset($data['content']) && is_array($data['content']) ? \Ux2Dev\Speedy\Dto\Model\Content::fromArray($data['content']) : null,
            payment: isset($data['payment']) && is_array($data['payment']) ? \Ux2Dev\Speedy\Dto\Model\Payment::fromArray($data['payment']) : null,
            shipmentNote: $data['shipmentNote'] ?? null,
            ref1: $data['ref1'] ?? null,
            ref2: $data['ref2'] ?? null,
            price: isset($data['price']) && is_array($data['price']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentPrice::fromArray($data['price']) : null,
            delivery: isset($data['delivery']) && is_array($data['delivery']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentDelivery::fromArray($data['delivery']) : null,
            primaryShipment: isset($data['primaryShipment']) && is_array($data['primaryShipment']) ? \Ux2Dev\Speedy\Dto\Model\PrimaryShipment::fromArray($data['primaryShipment']) : null,
            returnShipmentId: $data['returnShipmentId'] ?? null,
            redirectShipmentId: $data['redirectShipmentId'] ?? null,
            pendingShipment: $data['pendingShipment'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->error !== null) $out['error'] = $this->error->toArray();
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->sender !== null) $out['sender'] = $this->sender->toArray();
        if ($this->recipient !== null) $out['recipient'] = $this->recipient->toArray();
        if ($this->service !== null) $out['service'] = $this->service->toArray();
        if ($this->content !== null) $out['content'] = $this->content->toArray();
        if ($this->payment !== null) $out['payment'] = $this->payment->toArray();
        if ($this->shipmentNote !== null) $out['shipmentNote'] = $this->shipmentNote;
        if ($this->ref1 !== null) $out['ref1'] = $this->ref1;
        if ($this->ref2 !== null) $out['ref2'] = $this->ref2;
        if ($this->price !== null) $out['price'] = $this->price->toArray();
        if ($this->delivery !== null) $out['delivery'] = $this->delivery->toArray();
        if ($this->primaryShipment !== null) $out['primaryShipment'] = $this->primaryShipment->toArray();
        if ($this->returnShipmentId !== null) $out['returnShipmentId'] = $this->returnShipmentId;
        if ($this->redirectShipmentId !== null) $out['redirectShipmentId'] = $this->redirectShipmentId;
        if ($this->pendingShipment !== null) $out['pendingShipment'] = $this->pendingShipment;
        return $out;
    }
}
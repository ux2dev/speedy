<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Shipment;

final readonly class UpdateShipmentRequest
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentService $service = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentContent $content = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentPayment $payment = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentSender $sender = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentRecipient $recipient = null,
        public readonly ?bool $pendingShipment = null,
        public readonly ?string $shipmentNote = null,
        public readonly ?string $ref1 = null,
        public readonly ?string $ref2 = null,
        public readonly ?string $consolidationRef = null,
        public readonly ?bool $requireUnsuccessfulDeliveryStickerImage = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->service !== null) $out['service'] = $this->service->toArray();
        if ($this->content !== null) $out['content'] = $this->content->toArray();
        if ($this->payment !== null) $out['payment'] = $this->payment->toArray();
        if ($this->sender !== null) $out['sender'] = $this->sender->toArray();
        if ($this->recipient !== null) $out['recipient'] = $this->recipient->toArray();
        if ($this->pendingShipment !== null) $out['pendingShipment'] = $this->pendingShipment;
        if ($this->shipmentNote !== null) $out['shipmentNote'] = $this->shipmentNote;
        if ($this->ref1 !== null) $out['ref1'] = $this->ref1;
        if ($this->ref2 !== null) $out['ref2'] = $this->ref2;
        if ($this->consolidationRef !== null) $out['consolidationRef'] = $this->consolidationRef;
        if ($this->requireUnsuccessfulDeliveryStickerImage !== null) $out['requireUnsuccessfulDeliveryStickerImage'] = $this->requireUnsuccessfulDeliveryStickerImage;
        return $out;
    }
}
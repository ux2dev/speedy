<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentDelivery
{
    public function __construct(
        public readonly ?string $deadline = null,
        public readonly ?string $deliveryDateTime = null,
        public readonly ?string $consignee = null,
        public readonly ?string $deliveryNote = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            deadline: $data['deadline'] ?? null,
            deliveryDateTime: $data['deliveryDateTime'] ?? null,
            consignee: $data['consignee'] ?? null,
            deliveryNote: $data['deliveryNote'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->deadline !== null) $out['deadline'] = $this->deadline;
        if ($this->deliveryDateTime !== null) $out['deliveryDateTime'] = $this->deliveryDateTime;
        if ($this->consignee !== null) $out['consignee'] = $this->consignee;
        if ($this->deliveryNote !== null) $out['deliveryNote'] = $this->deliveryNote;
        return $out;
    }
}
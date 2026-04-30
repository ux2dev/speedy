<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentOBPD
{
    public function __construct(
        public readonly ?string $option = null,
        public readonly ?int $returnShipmentServiceId = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentRole $returnShipmentPayer = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            option: $data['option'] ?? null,
            returnShipmentServiceId: $data['returnShipmentServiceId'] ?? null,
            returnShipmentPayer: isset($data['returnShipmentPayer']) && is_array($data['returnShipmentPayer']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentRole::fromArray($data['returnShipmentPayer']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->option !== null) $out['option'] = $this->option;
        if ($this->returnShipmentServiceId !== null) $out['returnShipmentServiceId'] = $this->returnShipmentServiceId;
        if ($this->returnShipmentPayer !== null) $out['returnShipmentPayer'] = $this->returnShipmentPayer->toArray();
        return $out;
    }
}
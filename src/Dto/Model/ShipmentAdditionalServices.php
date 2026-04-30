<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentAdditionalServices
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentCODAdditionalService $cod = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentDeclaredValueAdditionalService $declaredValue = null,
        public readonly ?int $fixedTimeDelivery = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentReturnAdditionalServices $returns = null,
        public readonly ?int $specialDeliveryId = null,
        public readonly ?int $deliveryToFloor = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentOBPD $obpd = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            cod: isset($data['cod']) && is_array($data['cod']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentCODAdditionalService::fromArray($data['cod']) : null,
            declaredValue: isset($data['declaredValue']) && is_array($data['declaredValue']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentDeclaredValueAdditionalService::fromArray($data['declaredValue']) : null,
            fixedTimeDelivery: $data['fixedTimeDelivery'] ?? null,
            returns: isset($data['returns']) && is_array($data['returns']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentReturnAdditionalServices::fromArray($data['returns']) : null,
            specialDeliveryId: $data['specialDeliveryId'] ?? null,
            deliveryToFloor: $data['deliveryToFloor'] ?? null,
            obpd: isset($data['obpd']) && is_array($data['obpd']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentOBPD::fromArray($data['obpd']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->cod !== null) $out['cod'] = $this->cod->toArray();
        if ($this->declaredValue !== null) $out['declaredValue'] = $this->declaredValue->toArray();
        if ($this->fixedTimeDelivery !== null) $out['fixedTimeDelivery'] = $this->fixedTimeDelivery;
        if ($this->returns !== null) $out['returns'] = $this->returns->toArray();
        if ($this->specialDeliveryId !== null) $out['specialDeliveryId'] = $this->specialDeliveryId;
        if ($this->deliveryToFloor !== null) $out['deliveryToFloor'] = $this->deliveryToFloor;
        if ($this->obpd !== null) $out['obpd'] = $this->obpd->toArray();
        return $out;
    }
}
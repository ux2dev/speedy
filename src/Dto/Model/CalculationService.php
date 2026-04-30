<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class CalculationService
{
    public function __construct(
        public readonly ?string $pickupDate = null,
        public readonly ?bool $autoAdjustPickupDate = null,
        public readonly ?array $serviceIds = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentAdditionalServices $additionalServices = null,
        public readonly ?int $deferredDays = null,
        public readonly ?bool $saturdayDelivery = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            pickupDate: $data['pickupDate'] ?? null,
            autoAdjustPickupDate: $data['autoAdjustPickupDate'] ?? null,
            serviceIds: isset($data['serviceIds']) && is_array($data['serviceIds']) ? $data['serviceIds'] : null,
            additionalServices: isset($data['additionalServices']) && is_array($data['additionalServices']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentAdditionalServices::fromArray($data['additionalServices']) : null,
            deferredDays: $data['deferredDays'] ?? null,
            saturdayDelivery: $data['saturdayDelivery'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->pickupDate !== null) $out['pickupDate'] = $this->pickupDate;
        if ($this->autoAdjustPickupDate !== null) $out['autoAdjustPickupDate'] = $this->autoAdjustPickupDate;
        if ($this->serviceIds !== null) $out['serviceIds'] = $this->serviceIds;
        if ($this->additionalServices !== null) $out['additionalServices'] = $this->additionalServices->toArray();
        if ($this->deferredDays !== null) $out['deferredDays'] = $this->deferredDays;
        if ($this->saturdayDelivery !== null) $out['saturdayDelivery'] = $this->saturdayDelivery;
        return $out;
    }
}
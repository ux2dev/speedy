<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentService
{
    public function __construct(
        public readonly ?string $pickupDate = null,
        public readonly ?int $serviceId = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentAdditionalServices $additionalServices = null,
        public readonly ?int $deferredDays = null,
        public readonly ?bool $saturdayDelivery = null,
        public readonly ?bool $autoAdjustPickupDate = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\DeliveryLimitViolationAutoAdjustment $deliveryLimitViolationAutoAdjustment = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            pickupDate: $data['pickupDate'] ?? null,
            serviceId: $data['serviceId'] ?? null,
            additionalServices: isset($data['additionalServices']) && is_array($data['additionalServices']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentAdditionalServices::fromArray($data['additionalServices']) : null,
            deferredDays: $data['deferredDays'] ?? null,
            saturdayDelivery: $data['saturdayDelivery'] ?? null,
            autoAdjustPickupDate: $data['autoAdjustPickupDate'] ?? null,
            deliveryLimitViolationAutoAdjustment: isset($data['deliveryLimitViolationAutoAdjustment']) && is_array($data['deliveryLimitViolationAutoAdjustment']) ? \Ux2Dev\Speedy\Dto\Model\DeliveryLimitViolationAutoAdjustment::fromArray($data['deliveryLimitViolationAutoAdjustment']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->pickupDate !== null) $out['pickupDate'] = $this->pickupDate;
        if ($this->serviceId !== null) $out['serviceId'] = $this->serviceId;
        if ($this->additionalServices !== null) $out['additionalServices'] = $this->additionalServices->toArray();
        if ($this->deferredDays !== null) $out['deferredDays'] = $this->deferredDays;
        if ($this->saturdayDelivery !== null) $out['saturdayDelivery'] = $this->saturdayDelivery;
        if ($this->autoAdjustPickupDate !== null) $out['autoAdjustPickupDate'] = $this->autoAdjustPickupDate;
        if ($this->deliveryLimitViolationAutoAdjustment !== null) $out['deliveryLimitViolationAutoAdjustment'] = $this->deliveryLimitViolationAutoAdjustment->toArray();
        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class CalculationResult
{
    public function __construct(
        public readonly ?int $serviceId = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentPrice $price = null,
        public readonly ?string $pickupDate = null,
        public readonly ?string $deliveryDeadline = null,
        public readonly ?array $deliveryDeadlineWorkDayType = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentAdditionalServices $additionalServices = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            serviceId: $data['serviceId'] ?? null,
            price: isset($data['price']) && is_array($data['price']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentPrice::fromArray($data['price']) : null,
            pickupDate: $data['pickupDate'] ?? null,
            deliveryDeadline: $data['deliveryDeadline'] ?? null,
            deliveryDeadlineWorkDayType: isset($data['deliveryDeadlineWorkDayType']) && is_array($data['deliveryDeadlineWorkDayType']) ? $data['deliveryDeadlineWorkDayType'] : null,
            additionalServices: isset($data['additionalServices']) && is_array($data['additionalServices']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentAdditionalServices::fromArray($data['additionalServices']) : null,
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->serviceId !== null) $out['serviceId'] = $this->serviceId;
        if ($this->price !== null) $out['price'] = $this->price->toArray();
        if ($this->pickupDate !== null) $out['pickupDate'] = $this->pickupDate;
        if ($this->deliveryDeadline !== null) $out['deliveryDeadline'] = $this->deliveryDeadline;
        if ($this->deliveryDeadlineWorkDayType !== null) $out['deliveryDeadlineWorkDayType'] = $this->deliveryDeadlineWorkDayType;
        if ($this->additionalServices !== null) $out['additionalServices'] = $this->additionalServices->toArray();
        if ($this->error !== null) $out['error'] = $this->error->toArray();
        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Pickup;

final readonly class PickupRequest
{
    public function __construct(
        public readonly ?string $pickupDateTime = null,
        public readonly ?string $pickupScope = null,
        public readonly ?array $explicitShipmentIdList = null,
        public readonly ?string $visitEndTime = null,
        public readonly ?string $contactName = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentPhoneNumber $phoneNumber = null,
        public readonly ?bool $autoAdjustPickupDate = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->pickupDateTime !== null) $out['pickupDateTime'] = $this->pickupDateTime;
        if ($this->pickupScope !== null) $out['pickupScope'] = $this->pickupScope;
        if ($this->explicitShipmentIdList !== null) $out['explicitShipmentIdList'] = $this->explicitShipmentIdList;
        if ($this->visitEndTime !== null) $out['visitEndTime'] = $this->visitEndTime;
        if ($this->contactName !== null) $out['contactName'] = $this->contactName;
        if ($this->phoneNumber !== null) $out['phoneNumber'] = $this->phoneNumber->toArray();
        if ($this->autoAdjustPickupDate !== null) $out['autoAdjustPickupDate'] = $this->autoAdjustPickupDate;
        return $out;
    }
}
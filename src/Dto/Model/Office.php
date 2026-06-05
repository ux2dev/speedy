<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class Office
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?string $name = null,
        public readonly ?string $nameEn = null,
        public readonly ?int $siteId = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Address $address = null,
        public readonly ?string $workingTimeFrom = null,
        public readonly ?string $workingTimeTo = null,
        public readonly ?string $workingTimeHalfFrom = null,
        public readonly ?string $workingTimeHalfTo = null,
        public readonly ?string $workingTimeDayOffFrom = null,
        public readonly ?string $workingTimeDayOffTo = null,
        public readonly ?string $sameDayDepartureCutoff = null,
        public readonly ?string $sameDayDepartureCutoffHalf = null,
        public readonly ?string $sameDayDepartureCutoffDayOff = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentParcelSize $maxParcelDimensions = null,
        public readonly ?float $maxParcelWeight = null,
        public readonly ?string $type = null,
        public readonly ?int $vendor = null,
        public readonly ?int $nearbyOfficeId = null,
        public readonly ?array $workingTimeSchedule = null,
        public readonly ?string $validFrom = null,
        public readonly ?string $validTo = null,
        public readonly ?array $cargoTypesAllowed = null,
        public readonly ?bool $pickUpAllowed = null,
        public readonly ?bool $dropOffAllowed = null,
        public readonly ?array $routingInformation = null,
        public readonly ?bool $cashPaymentAllowed = null,
        public readonly ?bool $cardPaymentAllowed = null,
        public readonly ?bool $palletOffice = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            siteId: $data['siteId'] ?? null,
            address: isset($data['address']) && is_array($data['address']) ? \Ux2Dev\Speedy\Dto\Model\Address::fromArray($data['address']) : null,
            workingTimeFrom: $data['workingTimeFrom'] ?? null,
            workingTimeTo: $data['workingTimeTo'] ?? null,
            workingTimeHalfFrom: $data['workingTimeHalfFrom'] ?? null,
            workingTimeHalfTo: $data['workingTimeHalfTo'] ?? null,
            workingTimeDayOffFrom: $data['workingTimeDayOffFrom'] ?? null,
            workingTimeDayOffTo: $data['workingTimeDayOffTo'] ?? null,
            sameDayDepartureCutoff: $data['sameDayDepartureCutoff'] ?? null,
            sameDayDepartureCutoffHalf: $data['sameDayDepartureCutoffHalf'] ?? null,
            sameDayDepartureCutoffDayOff: $data['sameDayDepartureCutoffDayOff'] ?? null,
            maxParcelDimensions: isset($data['maxParcelDimensions']) && is_array($data['maxParcelDimensions']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentParcelSize::fromArray($data['maxParcelDimensions']) : null,
            maxParcelWeight: $data['maxParcelWeight'] ?? null,
            type: $data['type'] ?? null,
            vendor: $data['vendor'] ?? null,
            nearbyOfficeId: $data['nearbyOfficeId'] ?? null,
            workingTimeSchedule: isset($data['workingTimeSchedule']) && is_array($data['workingTimeSchedule']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\OfficeWorkingTimeSchedule::fromArray($r), $data['workingTimeSchedule']) : null,
            validFrom: $data['validFrom'] ?? null,
            validTo: $data['validTo'] ?? null,
            cargoTypesAllowed: isset($data['cargoTypesAllowed']) && is_array($data['cargoTypesAllowed']) ? $data['cargoTypesAllowed'] : null,
            pickUpAllowed: $data['pickUpAllowed'] ?? null,
            dropOffAllowed: $data['dropOffAllowed'] ?? null,
            routingInformation: isset($data['routingInformation']) && is_array($data['routingInformation']) ? $data['routingInformation'] : null,
            cashPaymentAllowed: $data['cashPaymentAllowed'] ?? null,
            cardPaymentAllowed: $data['cardPaymentAllowed'] ?? null,
            palletOffice: $data['palletOffice'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->name !== null) $out['name'] = $this->name;
        if ($this->nameEn !== null) $out['nameEn'] = $this->nameEn;
        if ($this->siteId !== null) $out['siteId'] = $this->siteId;
        if ($this->address !== null) $out['address'] = $this->address->toArray();
        if ($this->workingTimeFrom !== null) $out['workingTimeFrom'] = $this->workingTimeFrom;
        if ($this->workingTimeTo !== null) $out['workingTimeTo'] = $this->workingTimeTo;
        if ($this->workingTimeHalfFrom !== null) $out['workingTimeHalfFrom'] = $this->workingTimeHalfFrom;
        if ($this->workingTimeHalfTo !== null) $out['workingTimeHalfTo'] = $this->workingTimeHalfTo;
        if ($this->workingTimeDayOffFrom !== null) $out['workingTimeDayOffFrom'] = $this->workingTimeDayOffFrom;
        if ($this->workingTimeDayOffTo !== null) $out['workingTimeDayOffTo'] = $this->workingTimeDayOffTo;
        if ($this->sameDayDepartureCutoff !== null) $out['sameDayDepartureCutoff'] = $this->sameDayDepartureCutoff;
        if ($this->sameDayDepartureCutoffHalf !== null) $out['sameDayDepartureCutoffHalf'] = $this->sameDayDepartureCutoffHalf;
        if ($this->sameDayDepartureCutoffDayOff !== null) $out['sameDayDepartureCutoffDayOff'] = $this->sameDayDepartureCutoffDayOff;
        if ($this->maxParcelDimensions !== null) $out['maxParcelDimensions'] = $this->maxParcelDimensions->toArray();
        if ($this->maxParcelWeight !== null) $out['maxParcelWeight'] = $this->maxParcelWeight;
        if ($this->type !== null) $out['type'] = $this->type;
        if ($this->vendor !== null) $out['vendor'] = $this->vendor;
        if ($this->nearbyOfficeId !== null) $out['nearbyOfficeId'] = $this->nearbyOfficeId;
        if ($this->workingTimeSchedule !== null) $out['workingTimeSchedule'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\OfficeWorkingTimeSchedule $x) => $x->toArray(), $this->workingTimeSchedule);
        if ($this->validFrom !== null) $out['validFrom'] = $this->validFrom;
        if ($this->validTo !== null) $out['validTo'] = $this->validTo;
        if ($this->cargoTypesAllowed !== null) $out['cargoTypesAllowed'] = $this->cargoTypesAllowed;
        if ($this->pickUpAllowed !== null) $out['pickUpAllowed'] = $this->pickUpAllowed;
        if ($this->dropOffAllowed !== null) $out['dropOffAllowed'] = $this->dropOffAllowed;
        if ($this->routingInformation !== null) $out['routingInformation'] = $this->routingInformation;
        if ($this->cashPaymentAllowed !== null) $out['cashPaymentAllowed'] = $this->cashPaymentAllowed;
        if ($this->cardPaymentAllowed !== null) $out['cardPaymentAllowed'] = $this->cardPaymentAllowed;
        if ($this->palletOffice !== null) $out['palletOffice'] = $this->palletOffice;
        return $out;
    }
}
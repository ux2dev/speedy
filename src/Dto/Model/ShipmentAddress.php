<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentAddress
{
    public function __construct(
        public readonly ?int $countryId = null,
        public readonly ?string $stateId = null,
        public readonly ?int $siteId = null,
        public readonly ?string $siteType = null,
        public readonly ?string $siteName = null,
        public readonly ?string $postCode = null,
        public readonly ?int $streetId = null,
        public readonly ?string $streetType = null,
        public readonly ?string $streetName = null,
        public readonly ?string $streetNo = null,
        public readonly ?int $complexId = null,
        public readonly ?string $complexType = null,
        public readonly ?string $complexName = null,
        public readonly ?string $blockNo = null,
        public readonly ?string $entranceNo = null,
        public readonly ?string $floorNo = null,
        public readonly ?string $apartmentNo = null,
        public readonly ?int $poiId = null,
        public readonly ?string $addressNote = null,
        public readonly ?string $addressLine1 = null,
        public readonly ?string $addressLine2 = null,
        public readonly ?float $x = null,
        public readonly ?float $y = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            countryId: $data['countryId'] ?? null,
            stateId: $data['stateId'] ?? null,
            siteId: $data['siteId'] ?? null,
            siteType: $data['siteType'] ?? null,
            siteName: $data['siteName'] ?? null,
            postCode: $data['postCode'] ?? null,
            streetId: $data['streetId'] ?? null,
            streetType: $data['streetType'] ?? null,
            streetName: $data['streetName'] ?? null,
            streetNo: $data['streetNo'] ?? null,
            complexId: $data['complexId'] ?? null,
            complexType: $data['complexType'] ?? null,
            complexName: $data['complexName'] ?? null,
            blockNo: $data['blockNo'] ?? null,
            entranceNo: $data['entranceNo'] ?? null,
            floorNo: $data['floorNo'] ?? null,
            apartmentNo: $data['apartmentNo'] ?? null,
            poiId: $data['poiId'] ?? null,
            addressNote: $data['addressNote'] ?? null,
            addressLine1: $data['addressLine1'] ?? null,
            addressLine2: $data['addressLine2'] ?? null,
            x: $data['x'] ?? null,
            y: $data['y'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->countryId !== null) $out['countryId'] = $this->countryId;
        if ($this->stateId !== null) $out['stateId'] = $this->stateId;
        if ($this->siteId !== null) $out['siteId'] = $this->siteId;
        if ($this->siteType !== null) $out['siteType'] = $this->siteType;
        if ($this->siteName !== null) $out['siteName'] = $this->siteName;
        if ($this->postCode !== null) $out['postCode'] = $this->postCode;
        if ($this->streetId !== null) $out['streetId'] = $this->streetId;
        if ($this->streetType !== null) $out['streetType'] = $this->streetType;
        if ($this->streetName !== null) $out['streetName'] = $this->streetName;
        if ($this->streetNo !== null) $out['streetNo'] = $this->streetNo;
        if ($this->complexId !== null) $out['complexId'] = $this->complexId;
        if ($this->complexType !== null) $out['complexType'] = $this->complexType;
        if ($this->complexName !== null) $out['complexName'] = $this->complexName;
        if ($this->blockNo !== null) $out['blockNo'] = $this->blockNo;
        if ($this->entranceNo !== null) $out['entranceNo'] = $this->entranceNo;
        if ($this->floorNo !== null) $out['floorNo'] = $this->floorNo;
        if ($this->apartmentNo !== null) $out['apartmentNo'] = $this->apartmentNo;
        if ($this->poiId !== null) $out['poiId'] = $this->poiId;
        if ($this->addressNote !== null) $out['addressNote'] = $this->addressNote;
        if ($this->addressLine1 !== null) $out['addressLine1'] = $this->addressLine1;
        if ($this->addressLine2 !== null) $out['addressLine2'] = $this->addressLine2;
        if ($this->x !== null) $out['x'] = $this->x;
        if ($this->y !== null) $out['y'] = $this->y;
        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentSender
{
    public function __construct(
        public readonly ?int $clientId = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentPhoneNumber $phone1 = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentPhoneNumber $phone2 = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentPhoneNumber $phone3 = null,
        public readonly ?string $clientName = null,
        public readonly ?string $contactName = null,
        public readonly ?string $objectName = null,
        public readonly ?string $email = null,
        public readonly ?bool $privatePerson = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentAddress $address = null,
        public readonly ?int $dropoffOfficeId = null,
        public readonly ?string $dropoffGeoPUDOId = null,
        public readonly ?bool $dropoff = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            clientId: $data['clientId'] ?? null,
            phone1: isset($data['phone1']) && is_array($data['phone1']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentPhoneNumber::fromArray($data['phone1']) : null,
            phone2: isset($data['phone2']) && is_array($data['phone2']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentPhoneNumber::fromArray($data['phone2']) : null,
            phone3: isset($data['phone3']) && is_array($data['phone3']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentPhoneNumber::fromArray($data['phone3']) : null,
            clientName: $data['clientName'] ?? null,
            contactName: $data['contactName'] ?? null,
            objectName: $data['objectName'] ?? null,
            email: $data['email'] ?? null,
            privatePerson: $data['privatePerson'] ?? null,
            address: isset($data['address']) && is_array($data['address']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentAddress::fromArray($data['address']) : null,
            dropoffOfficeId: $data['dropoffOfficeId'] ?? null,
            dropoffGeoPUDOId: $data['dropoffGeoPUDOId'] ?? null,
            dropoff: $data['dropoff'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->clientId !== null) $out['clientId'] = $this->clientId;
        if ($this->phone1 !== null) $out['phone1'] = $this->phone1->toArray();
        if ($this->phone2 !== null) $out['phone2'] = $this->phone2->toArray();
        if ($this->phone3 !== null) $out['phone3'] = $this->phone3->toArray();
        if ($this->clientName !== null) $out['clientName'] = $this->clientName;
        if ($this->contactName !== null) $out['contactName'] = $this->contactName;
        if ($this->objectName !== null) $out['objectName'] = $this->objectName;
        if ($this->email !== null) $out['email'] = $this->email;
        if ($this->privatePerson !== null) $out['privatePerson'] = $this->privatePerson;
        if ($this->address !== null) $out['address'] = $this->address->toArray();
        if ($this->dropoffOfficeId !== null) $out['dropoffOfficeId'] = $this->dropoffOfficeId;
        if ($this->dropoffGeoPUDOId !== null) $out['dropoffGeoPUDOId'] = $this->dropoffGeoPUDOId;
        if ($this->dropoff !== null) $out['dropoff'] = $this->dropoff;
        return $out;
    }
}
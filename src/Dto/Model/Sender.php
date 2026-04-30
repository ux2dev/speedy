<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class Sender
{
    public function __construct(
        public readonly ?int $clientId = null,
        public readonly ?string $clientName = null,
        public readonly ?string $objectName = null,
        public readonly ?string $contactName = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Address $address = null,
        public readonly ?string $email = null,
        public readonly ?bool $privatePerson = null,
        public readonly ?array $phones = null,
        public readonly ?string $externalContactId = null,
        public readonly ?int $dropoffOfficeId = null,
        public readonly ?string $dropoffGeoPUDOId = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            clientId: $data['clientId'] ?? null,
            clientName: $data['clientName'] ?? null,
            objectName: $data['objectName'] ?? null,
            contactName: $data['contactName'] ?? null,
            address: isset($data['address']) && is_array($data['address']) ? \Ux2Dev\Speedy\Dto\Model\Address::fromArray($data['address']) : null,
            email: $data['email'] ?? null,
            privatePerson: $data['privatePerson'] ?? null,
            phones: isset($data['phones']) && is_array($data['phones']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\ShipmentPhoneNumber::fromArray($r), $data['phones']) : null,
            externalContactId: $data['externalContactId'] ?? null,
            dropoffOfficeId: $data['dropoffOfficeId'] ?? null,
            dropoffGeoPUDOId: $data['dropoffGeoPUDOId'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->clientId !== null) $out['clientId'] = $this->clientId;
        if ($this->clientName !== null) $out['clientName'] = $this->clientName;
        if ($this->objectName !== null) $out['objectName'] = $this->objectName;
        if ($this->contactName !== null) $out['contactName'] = $this->contactName;
        if ($this->address !== null) $out['address'] = $this->address->toArray();
        if ($this->email !== null) $out['email'] = $this->email;
        if ($this->privatePerson !== null) $out['privatePerson'] = $this->privatePerson;
        if ($this->phones !== null) $out['phones'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\ShipmentPhoneNumber $x) => $x->toArray(), $this->phones);
        if ($this->externalContactId !== null) $out['externalContactId'] = $this->externalContactId;
        if ($this->dropoffOfficeId !== null) $out['dropoffOfficeId'] = $this->dropoffOfficeId;
        if ($this->dropoffGeoPUDOId !== null) $out['dropoffGeoPUDOId'] = $this->dropoffGeoPUDOId;
        return $out;
    }
}
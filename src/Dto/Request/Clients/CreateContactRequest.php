<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Clients;

final readonly class CreateContactRequest
{
    public function __construct(
        public readonly ?string $externalContactId = null,
        public readonly ?string $secretKey = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentPhoneNumber $phone1 = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentPhoneNumber $phone2 = null,
        public readonly ?string $clientName = null,
        public readonly ?string $objectName = null,
        public readonly ?string $contactName = null,
        public readonly ?string $email = null,
        public readonly ?bool $privatePerson = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentAddress $address = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->externalContactId !== null) $out['externalContactId'] = $this->externalContactId;
        if ($this->secretKey !== null) $out['secretKey'] = $this->secretKey;
        if ($this->phone1 !== null) $out['phone1'] = $this->phone1->toArray();
        if ($this->phone2 !== null) $out['phone2'] = $this->phone2->toArray();
        if ($this->clientName !== null) $out['clientName'] = $this->clientName;
        if ($this->objectName !== null) $out['objectName'] = $this->objectName;
        if ($this->contactName !== null) $out['contactName'] = $this->contactName;
        if ($this->email !== null) $out['email'] = $this->email;
        if ($this->privatePerson !== null) $out['privatePerson'] = $this->privatePerson;
        if ($this->address !== null) $out['address'] = $this->address->toArray();
        return $out;
    }
}
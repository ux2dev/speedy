<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class CalculationRecipient
{
    public function __construct(
        public readonly ?int $clientId = null,
        public readonly ?bool $privatePerson = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\AddressLocation $addressLocation = null,
        public readonly ?int $pickupOfficeId = null,
        public readonly ?string $pickupGeoPUDOId = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            clientId: $data['clientId'] ?? null,
            privatePerson: $data['privatePerson'] ?? null,
            addressLocation: isset($data['addressLocation']) && is_array($data['addressLocation']) ? \Ux2Dev\Speedy\Dto\Model\AddressLocation::fromArray($data['addressLocation']) : null,
            pickupOfficeId: $data['pickupOfficeId'] ?? null,
            pickupGeoPUDOId: $data['pickupGeoPUDOId'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->clientId !== null) $out['clientId'] = $this->clientId;
        if ($this->privatePerson !== null) $out['privatePerson'] = $this->privatePerson;
        if ($this->addressLocation !== null) $out['addressLocation'] = $this->addressLocation->toArray();
        if ($this->pickupOfficeId !== null) $out['pickupOfficeId'] = $this->pickupOfficeId;
        if ($this->pickupGeoPUDOId !== null) $out['pickupGeoPUDOId'] = $this->pickupGeoPUDOId;
        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class CalculationSender
{
    public function __construct(
        public readonly ?int $clientId = null,
        public readonly ?bool $privatePerson = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\AddressLocation $addressLocation = null,
        public readonly ?bool $dropoff = null,
        public readonly ?int $dropoffOfficeId = null,
        public readonly ?string $dropoffGeoPUDOId = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            clientId: $data['clientId'] ?? null,
            privatePerson: $data['privatePerson'] ?? null,
            addressLocation: isset($data['addressLocation']) && is_array($data['addressLocation']) ? \Ux2Dev\Speedy\Dto\Model\AddressLocation::fromArray($data['addressLocation']) : null,
            dropoff: $data['dropoff'] ?? null,
            dropoffOfficeId: $data['dropoffOfficeId'] ?? null,
            dropoffGeoPUDOId: $data['dropoffGeoPUDOId'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->clientId !== null) $out['clientId'] = $this->clientId;
        if ($this->privatePerson !== null) $out['privatePerson'] = $this->privatePerson;
        if ($this->addressLocation !== null) $out['addressLocation'] = $this->addressLocation->toArray();
        if ($this->dropoff !== null) $out['dropoff'] = $this->dropoff;
        if ($this->dropoffOfficeId !== null) $out['dropoffOfficeId'] = $this->dropoffOfficeId;
        if ($this->dropoffGeoPUDOId !== null) $out['dropoffGeoPUDOId'] = $this->dropoffGeoPUDOId;
        return $out;
    }
}
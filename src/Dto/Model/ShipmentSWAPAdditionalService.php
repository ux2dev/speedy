<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentSWAPAdditionalService
{
    public function __construct(
        public readonly ?int $serviceId = null,
        public readonly ?int $parcelsCount = null,
        public readonly ?float $declaredValue = null,
        public readonly ?bool $fragile = null,
        public readonly ?int $returnToClientId = null,
        public readonly ?int $returnToOfficeId = null,
        public readonly ?bool $thirdPartyPayer = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            serviceId: $data['serviceId'] ?? null,
            parcelsCount: $data['parcelsCount'] ?? null,
            declaredValue: $data['declaredValue'] ?? null,
            fragile: $data['fragile'] ?? null,
            returnToClientId: $data['returnToClientId'] ?? null,
            returnToOfficeId: $data['returnToOfficeId'] ?? null,
            thirdPartyPayer: $data['thirdPartyPayer'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->serviceId !== null) $out['serviceId'] = $this->serviceId;
        if ($this->parcelsCount !== null) $out['parcelsCount'] = $this->parcelsCount;
        if ($this->declaredValue !== null) $out['declaredValue'] = $this->declaredValue;
        if ($this->fragile !== null) $out['fragile'] = $this->fragile;
        if ($this->returnToClientId !== null) $out['returnToClientId'] = $this->returnToClientId;
        if ($this->returnToOfficeId !== null) $out['returnToOfficeId'] = $this->returnToOfficeId;
        if ($this->thirdPartyPayer !== null) $out['thirdPartyPayer'] = $this->thirdPartyPayer;
        return $out;
    }
}
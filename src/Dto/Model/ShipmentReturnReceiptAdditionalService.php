<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentReturnReceiptAdditionalService
{
    public function __construct(
        public readonly ?bool $enabled = null,
        public readonly ?int $returnToClientId = null,
        public readonly ?int $returnToOfficeId = null,
        public readonly ?bool $thirdPartyPayer = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            enabled: $data['enabled'] ?? null,
            returnToClientId: $data['returnToClientId'] ?? null,
            returnToOfficeId: $data['returnToOfficeId'] ?? null,
            thirdPartyPayer: $data['thirdPartyPayer'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->enabled !== null) $out['enabled'] = $this->enabled;
        if ($this->returnToClientId !== null) $out['returnToClientId'] = $this->returnToClientId;
        if ($this->returnToOfficeId !== null) $out['returnToOfficeId'] = $this->returnToOfficeId;
        if ($this->thirdPartyPayer !== null) $out['thirdPartyPayer'] = $this->thirdPartyPayer;
        return $out;
    }
}
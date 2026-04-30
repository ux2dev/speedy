<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentElectronicReturnReceiptAdditionalService
{
    public function __construct(
        public readonly ?array $recipientEmails = null,
        public readonly ?bool $thirdPartyPayer = null,
        public readonly ?int $returnToClientId = null,
        public readonly ?int $returnToOfficeId = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            recipientEmails: isset($data['recipientEmails']) && is_array($data['recipientEmails']) ? $data['recipientEmails'] : null,
            thirdPartyPayer: $data['thirdPartyPayer'] ?? null,
            returnToClientId: $data['returnToClientId'] ?? null,
            returnToOfficeId: $data['returnToOfficeId'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->recipientEmails !== null) $out['recipientEmails'] = $this->recipientEmails;
        if ($this->thirdPartyPayer !== null) $out['thirdPartyPayer'] = $this->thirdPartyPayer;
        if ($this->returnToClientId !== null) $out['returnToClientId'] = $this->returnToClientId;
        if ($this->returnToOfficeId !== null) $out['returnToOfficeId'] = $this->returnToOfficeId;
        return $out;
    }
}
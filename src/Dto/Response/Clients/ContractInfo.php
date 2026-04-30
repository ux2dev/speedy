<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Clients;

final class ContractInfo
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?int $id = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\SpecialDeliveryRequirements $specialDeliveryRequirements = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\CODAdditionalServiceContractInfo $cod = null,
        public readonly ?bool $administrativeFeeAllowed = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            id: $data['id'] ?? null,
            specialDeliveryRequirements: isset($data['specialDeliveryRequirements']) && is_array($data['specialDeliveryRequirements']) ? \Ux2Dev\Speedy\Dto\Model\SpecialDeliveryRequirements::fromArray($data['specialDeliveryRequirements']) : null,
            cod: isset($data['cod']) && is_array($data['cod']) ? \Ux2Dev\Speedy\Dto\Model\CODAdditionalServiceContractInfo::fromArray($data['cod']) : null,
            administrativeFeeAllowed: $data['administrativeFeeAllowed'] ?? null,
        );
    }
}
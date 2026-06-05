<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentPayment
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentRole $courierServicePayer = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentRole $declaredValuePayer = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentRole $packagePayer = null,
        public readonly ?int $thirdPartyClientId = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentDiscountCardId $discountCardId = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\BankAccount $senderBankAccount = null,
        public readonly ?bool $administrativeFee = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            courierServicePayer: isset($data['courierServicePayer']) && is_string($data['courierServicePayer']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentRole::tryFrom($data['courierServicePayer']) : null,
            declaredValuePayer: isset($data['declaredValuePayer']) && is_string($data['declaredValuePayer']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentRole::tryFrom($data['declaredValuePayer']) : null,
            packagePayer: isset($data['packagePayer']) && is_string($data['packagePayer']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentRole::tryFrom($data['packagePayer']) : null,
            thirdPartyClientId: $data['thirdPartyClientId'] ?? null,
            discountCardId: isset($data['discountCardId']) && is_array($data['discountCardId']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentDiscountCardId::fromArray($data['discountCardId']) : null,
            senderBankAccount: isset($data['senderBankAccount']) && is_array($data['senderBankAccount']) ? \Ux2Dev\Speedy\Dto\Model\BankAccount::fromArray($data['senderBankAccount']) : null,
            administrativeFee: $data['administrativeFee'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->courierServicePayer !== null) $out['courierServicePayer'] = $this->courierServicePayer->value;
        if ($this->declaredValuePayer !== null) $out['declaredValuePayer'] = $this->declaredValuePayer->value;
        if ($this->packagePayer !== null) $out['packagePayer'] = $this->packagePayer->value;
        if ($this->thirdPartyClientId !== null) $out['thirdPartyClientId'] = $this->thirdPartyClientId;
        if ($this->discountCardId !== null) $out['discountCardId'] = $this->discountCardId->toArray();
        if ($this->senderBankAccount !== null) $out['senderBankAccount'] = $this->senderBankAccount->toArray();
        if ($this->administrativeFee !== null) $out['administrativeFee'] = $this->administrativeFee;
        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class Payment
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentRole $courierServicePayer = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentRole $declaredValuePayer = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentRole $packagePayer = null,
        public readonly ?int $thirdPartyClientId = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentDiscountCardId $discountCardId = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\CODPayment $codPayment = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            courierServicePayer: isset($data['courierServicePayer']) && is_array($data['courierServicePayer']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentRole::fromArray($data['courierServicePayer']) : null,
            declaredValuePayer: isset($data['declaredValuePayer']) && is_array($data['declaredValuePayer']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentRole::fromArray($data['declaredValuePayer']) : null,
            packagePayer: isset($data['packagePayer']) && is_array($data['packagePayer']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentRole::fromArray($data['packagePayer']) : null,
            thirdPartyClientId: $data['thirdPartyClientId'] ?? null,
            discountCardId: isset($data['discountCardId']) && is_array($data['discountCardId']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentDiscountCardId::fromArray($data['discountCardId']) : null,
            codPayment: isset($data['codPayment']) && is_array($data['codPayment']) ? \Ux2Dev\Speedy\Dto\Model\CODPayment::fromArray($data['codPayment']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->courierServicePayer !== null) $out['courierServicePayer'] = $this->courierServicePayer->toArray();
        if ($this->declaredValuePayer !== null) $out['declaredValuePayer'] = $this->declaredValuePayer->toArray();
        if ($this->packagePayer !== null) $out['packagePayer'] = $this->packagePayer->toArray();
        if ($this->thirdPartyClientId !== null) $out['thirdPartyClientId'] = $this->thirdPartyClientId;
        if ($this->discountCardId !== null) $out['discountCardId'] = $this->discountCardId->toArray();
        if ($this->codPayment !== null) $out['codPayment'] = $this->codPayment->toArray();
        return $out;
    }
}
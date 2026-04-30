<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentReturnVoucherAdditionalService
{
    public function __construct(
        public readonly ?int $serviceId = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentRole $payer = null,
        public readonly ?int $validityPeriod = null,
        public readonly ?string $externalReturnVoucherId = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            serviceId: $data['serviceId'] ?? null,
            payer: isset($data['payer']) && is_array($data['payer']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentRole::fromArray($data['payer']) : null,
            validityPeriod: $data['validityPeriod'] ?? null,
            externalReturnVoucherId: $data['externalReturnVoucherId'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->serviceId !== null) $out['serviceId'] = $this->serviceId;
        if ($this->payer !== null) $out['payer'] = $this->payer->toArray();
        if ($this->validityPeriod !== null) $out['validityPeriod'] = $this->validityPeriod;
        if ($this->externalReturnVoucherId !== null) $out['externalReturnVoucherId'] = $this->externalReturnVoucherId;
        return $out;
    }
}
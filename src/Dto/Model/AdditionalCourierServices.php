<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class AdditionalCourierServices
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\AdditionalCourierService $cod = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\AdditionalCourierService $obpd = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\AdditionalCourierService $declaredValue = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\AdditionalCourierService $fixedTimeDelivery = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\AdditionalCourierService $specialDelivery = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\AdditionalCourierService $deliveryToFloor = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\AdditionalCourierService $rod = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\AdditionalCourierService $returnReceipt = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\AdditionalCourierService $swap = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\AdditionalCourierService $rop = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\AdditionalCourierService $returnVoucher = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            cod: isset($data['cod']) && is_array($data['cod']) ? \Ux2Dev\Speedy\Dto\Model\AdditionalCourierService::fromArray($data['cod']) : null,
            obpd: isset($data['obpd']) && is_array($data['obpd']) ? \Ux2Dev\Speedy\Dto\Model\AdditionalCourierService::fromArray($data['obpd']) : null,
            declaredValue: isset($data['declaredValue']) && is_array($data['declaredValue']) ? \Ux2Dev\Speedy\Dto\Model\AdditionalCourierService::fromArray($data['declaredValue']) : null,
            fixedTimeDelivery: isset($data['fixedTimeDelivery']) && is_array($data['fixedTimeDelivery']) ? \Ux2Dev\Speedy\Dto\Model\AdditionalCourierService::fromArray($data['fixedTimeDelivery']) : null,
            specialDelivery: isset($data['specialDelivery']) && is_array($data['specialDelivery']) ? \Ux2Dev\Speedy\Dto\Model\AdditionalCourierService::fromArray($data['specialDelivery']) : null,
            deliveryToFloor: isset($data['deliveryToFloor']) && is_array($data['deliveryToFloor']) ? \Ux2Dev\Speedy\Dto\Model\AdditionalCourierService::fromArray($data['deliveryToFloor']) : null,
            rod: isset($data['rod']) && is_array($data['rod']) ? \Ux2Dev\Speedy\Dto\Model\AdditionalCourierService::fromArray($data['rod']) : null,
            returnReceipt: isset($data['returnReceipt']) && is_array($data['returnReceipt']) ? \Ux2Dev\Speedy\Dto\Model\AdditionalCourierService::fromArray($data['returnReceipt']) : null,
            swap: isset($data['swap']) && is_array($data['swap']) ? \Ux2Dev\Speedy\Dto\Model\AdditionalCourierService::fromArray($data['swap']) : null,
            rop: isset($data['rop']) && is_array($data['rop']) ? \Ux2Dev\Speedy\Dto\Model\AdditionalCourierService::fromArray($data['rop']) : null,
            returnVoucher: isset($data['returnVoucher']) && is_array($data['returnVoucher']) ? \Ux2Dev\Speedy\Dto\Model\AdditionalCourierService::fromArray($data['returnVoucher']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->cod !== null) $out['cod'] = $this->cod->toArray();
        if ($this->obpd !== null) $out['obpd'] = $this->obpd->toArray();
        if ($this->declaredValue !== null) $out['declaredValue'] = $this->declaredValue->toArray();
        if ($this->fixedTimeDelivery !== null) $out['fixedTimeDelivery'] = $this->fixedTimeDelivery->toArray();
        if ($this->specialDelivery !== null) $out['specialDelivery'] = $this->specialDelivery->toArray();
        if ($this->deliveryToFloor !== null) $out['deliveryToFloor'] = $this->deliveryToFloor->toArray();
        if ($this->rod !== null) $out['rod'] = $this->rod->toArray();
        if ($this->returnReceipt !== null) $out['returnReceipt'] = $this->returnReceipt->toArray();
        if ($this->swap !== null) $out['swap'] = $this->swap->toArray();
        if ($this->rop !== null) $out['rop'] = $this->rop->toArray();
        if ($this->returnVoucher !== null) $out['returnVoucher'] = $this->returnVoucher->toArray();
        return $out;
    }
}
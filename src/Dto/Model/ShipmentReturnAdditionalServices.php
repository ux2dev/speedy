<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentReturnAdditionalServices
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentRODAdditionalService $rod = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentReturnReceiptAdditionalService $returnReceipt = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentElectronicReturnReceiptAdditionalService $electronicReturnReceipt = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentSWAPAdditionalService $swap = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentROPAdditionalService $rop = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentReturnVoucherAdditionalService $returnVoucher = null,
        public readonly ?int $sendBackClientId = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            rod: isset($data['rod']) && is_array($data['rod']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentRODAdditionalService::fromArray($data['rod']) : null,
            returnReceipt: isset($data['returnReceipt']) && is_array($data['returnReceipt']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentReturnReceiptAdditionalService::fromArray($data['returnReceipt']) : null,
            electronicReturnReceipt: isset($data['electronicReturnReceipt']) && is_array($data['electronicReturnReceipt']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentElectronicReturnReceiptAdditionalService::fromArray($data['electronicReturnReceipt']) : null,
            swap: isset($data['swap']) && is_array($data['swap']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentSWAPAdditionalService::fromArray($data['swap']) : null,
            rop: isset($data['rop']) && is_array($data['rop']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentROPAdditionalService::fromArray($data['rop']) : null,
            returnVoucher: isset($data['returnVoucher']) && is_array($data['returnVoucher']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentReturnVoucherAdditionalService::fromArray($data['returnVoucher']) : null,
            sendBackClientId: $data['sendBackClientId'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->rod !== null) $out['rod'] = $this->rod->toArray();
        if ($this->returnReceipt !== null) $out['returnReceipt'] = $this->returnReceipt->toArray();
        if ($this->electronicReturnReceipt !== null) $out['electronicReturnReceipt'] = $this->electronicReturnReceipt->toArray();
        if ($this->swap !== null) $out['swap'] = $this->swap->toArray();
        if ($this->rop !== null) $out['rop'] = $this->rop->toArray();
        if ($this->returnVoucher !== null) $out['returnVoucher'] = $this->returnVoucher->toArray();
        if ($this->sendBackClientId !== null) $out['sendBackClientId'] = $this->sendBackClientId;
        return $out;
    }
}
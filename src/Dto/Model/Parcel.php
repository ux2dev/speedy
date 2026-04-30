<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class Parcel
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?int $seqNo = null,
        public readonly ?int $packageUniqueNumber = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentParcelSize $declaredSize = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentParcelSize $measuredSize = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentParcelSize $calculationSize = null,
        public readonly ?float $declaredWeight = null,
        public readonly ?float $measuredWeight = null,
        public readonly ?float $calculationWeight = null,
        public readonly ?array $externalCarrierParcelNumbers = null,
        public readonly ?string $baseType = null,
        public readonly ?string $size = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            seqNo: $data['seqNo'] ?? null,
            packageUniqueNumber: $data['packageUniqueNumber'] ?? null,
            declaredSize: isset($data['declaredSize']) && is_array($data['declaredSize']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentParcelSize::fromArray($data['declaredSize']) : null,
            measuredSize: isset($data['measuredSize']) && is_array($data['measuredSize']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentParcelSize::fromArray($data['measuredSize']) : null,
            calculationSize: isset($data['calculationSize']) && is_array($data['calculationSize']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentParcelSize::fromArray($data['calculationSize']) : null,
            declaredWeight: $data['declaredWeight'] ?? null,
            measuredWeight: $data['measuredWeight'] ?? null,
            calculationWeight: $data['calculationWeight'] ?? null,
            externalCarrierParcelNumbers: isset($data['externalCarrierParcelNumbers']) && is_array($data['externalCarrierParcelNumbers']) ? $data['externalCarrierParcelNumbers'] : null,
            baseType: $data['baseType'] ?? null,
            size: $data['size'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->seqNo !== null) $out['seqNo'] = $this->seqNo;
        if ($this->packageUniqueNumber !== null) $out['packageUniqueNumber'] = $this->packageUniqueNumber;
        if ($this->declaredSize !== null) $out['declaredSize'] = $this->declaredSize->toArray();
        if ($this->measuredSize !== null) $out['measuredSize'] = $this->measuredSize->toArray();
        if ($this->calculationSize !== null) $out['calculationSize'] = $this->calculationSize->toArray();
        if ($this->declaredWeight !== null) $out['declaredWeight'] = $this->declaredWeight;
        if ($this->measuredWeight !== null) $out['measuredWeight'] = $this->measuredWeight;
        if ($this->calculationWeight !== null) $out['calculationWeight'] = $this->calculationWeight;
        if ($this->externalCarrierParcelNumbers !== null) $out['externalCarrierParcelNumbers'] = $this->externalCarrierParcelNumbers;
        if ($this->baseType !== null) $out['baseType'] = $this->baseType;
        if ($this->size !== null) $out['size'] = $this->size;
        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class CreatedShipmentParcel
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?int $seqNo = null,
        public readonly ?int $externalCarrierId = null,
        public readonly ?string $externalCarrierParcelNumber = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            seqNo: $data['seqNo'] ?? null,
            externalCarrierId: $data['externalCarrierId'] ?? null,
            externalCarrierParcelNumber: $data['externalCarrierParcelNumber'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->seqNo !== null) $out['seqNo'] = $this->seqNo;
        if ($this->externalCarrierId !== null) $out['externalCarrierId'] = $this->externalCarrierId;
        if ($this->externalCarrierParcelNumber !== null) $out['externalCarrierParcelNumber'] = $this->externalCarrierParcelNumber;
        return $out;
    }
}
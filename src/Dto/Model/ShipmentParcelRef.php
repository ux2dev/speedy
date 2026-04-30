<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentParcelRef
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?string $externalCarrierParcelNumber = null,
        public readonly ?string $fullBarcode = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            externalCarrierParcelNumber: $data['externalCarrierParcelNumber'] ?? null,
            fullBarcode: $data['fullBarcode'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->externalCarrierParcelNumber !== null) $out['externalCarrierParcelNumber'] = $this->externalCarrierParcelNumber;
        if ($this->fullBarcode !== null) $out['fullBarcode'] = $this->fullBarcode;
        return $out;
    }
}
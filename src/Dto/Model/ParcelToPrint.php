<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ParcelToPrint
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ParcelToPrintAdditionalBarcode $additionalBarcode = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentParcelRef $parcelId = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            additionalBarcode: isset($data['additionalBarcode']) && is_array($data['additionalBarcode']) ? \Ux2Dev\Speedy\Dto\Model\ParcelToPrintAdditionalBarcode::fromArray($data['additionalBarcode']) : null,
            parcelId: isset($data['parcelId']) && is_array($data['parcelId']) ? \Ux2Dev\Speedy\Dto\Model\ShipmentParcelRef::fromArray($data['parcelId']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->additionalBarcode !== null) $out['additionalBarcode'] = $this->additionalBarcode->toArray();
        if ($this->parcelId !== null) $out['parcelId'] = $this->parcelId->toArray();
        return $out;
    }
}
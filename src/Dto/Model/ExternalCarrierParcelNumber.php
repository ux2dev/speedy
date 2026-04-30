<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ExternalCarrierParcelNumber
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ExternalCarrier $externalCarrier = null,
        public readonly ?string $parcelNumber = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            externalCarrier: isset($data['externalCarrier']) && is_array($data['externalCarrier']) ? \Ux2Dev\Speedy\Dto\Model\ExternalCarrier::fromArray($data['externalCarrier']) : null,
            parcelNumber: $data['parcelNumber'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->externalCarrier !== null) $out['externalCarrier'] = $this->externalCarrier->toArray();
        if ($this->parcelNumber !== null) $out['parcelNumber'] = $this->parcelNumber;
        return $out;
    }
}
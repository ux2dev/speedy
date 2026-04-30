<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class TrackedParcel
{
    public function __construct(
        public readonly ?string $parcelId = null,
        public readonly ?array $externalCarrierParcelNumbers = null,
        public readonly ?array $operations = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?string $ref = null,
        public readonly ?string $trackPhase = null,
        public readonly mixed $externalCarrierParcelNumbersDetails = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            parcelId: $data['parcelId'] ?? null,
            externalCarrierParcelNumbers: isset($data['externalCarrierParcelNumbers']) && is_array($data['externalCarrierParcelNumbers']) ? $data['externalCarrierParcelNumbers'] : null,
            operations: isset($data['operations']) && is_array($data['operations']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\TrackedParcelOperation::fromArray($r), $data['operations']) : null,
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            ref: $data['ref'] ?? null,
            trackPhase: $data['trackPhase'] ?? null,
            externalCarrierParcelNumbersDetails: $data['externalCarrierParcelNumbersDetails'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->parcelId !== null) $out['parcelId'] = $this->parcelId;
        if ($this->externalCarrierParcelNumbers !== null) $out['externalCarrierParcelNumbers'] = $this->externalCarrierParcelNumbers;
        if ($this->operations !== null) $out['operations'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\TrackedParcelOperation $x) => $x->toArray(), $this->operations);
        if ($this->error !== null) $out['error'] = $this->error->toArray();
        if ($this->ref !== null) $out['ref'] = $this->ref;
        if ($this->trackPhase !== null) $out['trackPhase'] = $this->trackPhase;
        if ($this->externalCarrierParcelNumbersDetails !== null) $out['externalCarrierParcelNumbersDetails'] = $this->externalCarrierParcelNumbersDetails;
        return $out;
    }
}
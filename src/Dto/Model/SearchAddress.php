<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class SearchAddress
{
    public function __construct(
        public readonly ?string $text = null,
        public readonly ?float $coordX = null,
        public readonly ?float $coordY = null,
        public readonly ?int $microregionId = null,
        public readonly ?float $distanceToSiteCenter = null,
        public readonly ?bool $actual = null,
        public readonly ?int $coordType = null,
        public readonly ?int $additionalAddressProcessing = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            text: $data['text'] ?? null,
            coordX: $data['coordX'] ?? null,
            coordY: $data['coordY'] ?? null,
            microregionId: $data['microregionId'] ?? null,
            distanceToSiteCenter: $data['distanceToSiteCenter'] ?? null,
            actual: $data['actual'] ?? null,
            coordType: $data['coordType'] ?? null,
            additionalAddressProcessing: $data['additionalAddressProcessing'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->text !== null) $out['text'] = $this->text;
        if ($this->coordX !== null) $out['coordX'] = $this->coordX;
        if ($this->coordY !== null) $out['coordY'] = $this->coordY;
        if ($this->microregionId !== null) $out['microregionId'] = $this->microregionId;
        if ($this->distanceToSiteCenter !== null) $out['distanceToSiteCenter'] = $this->distanceToSiteCenter;
        if ($this->actual !== null) $out['actual'] = $this->actual;
        if ($this->coordType !== null) $out['coordType'] = $this->coordType;
        if ($this->additionalAddressProcessing !== null) $out['additionalAddressProcessing'] = $this->additionalAddressProcessing;
        return $out;
    }
}
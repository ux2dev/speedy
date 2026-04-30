<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class CourierService
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?string $name = null,
        public readonly ?string $nameEn = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\AdditionalCourierServices $additionalServices = null,
        public readonly ?string $cargoType = null,
        public readonly ?bool $requireParcelWeight = null,
        public readonly ?bool $requireParcelSize = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            nameEn: $data['nameEn'] ?? null,
            additionalServices: isset($data['additionalServices']) && is_array($data['additionalServices']) ? \Ux2Dev\Speedy\Dto\Model\AdditionalCourierServices::fromArray($data['additionalServices']) : null,
            cargoType: $data['cargoType'] ?? null,
            requireParcelWeight: $data['requireParcelWeight'] ?? null,
            requireParcelSize: $data['requireParcelSize'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->name !== null) $out['name'] = $this->name;
        if ($this->nameEn !== null) $out['nameEn'] = $this->nameEn;
        if ($this->additionalServices !== null) $out['additionalServices'] = $this->additionalServices->toArray();
        if ($this->cargoType !== null) $out['cargoType'] = $this->cargoType;
        if ($this->requireParcelWeight !== null) $out['requireParcelWeight'] = $this->requireParcelWeight;
        if ($this->requireParcelSize !== null) $out['requireParcelSize'] = $this->requireParcelSize;
        return $out;
    }
}
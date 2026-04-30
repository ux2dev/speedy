<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentROPAdditionalServiceLine
{
    public function __construct(
        public readonly ?int $serviceId = null,
        public readonly ?int $parcelsCount = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            serviceId: $data['serviceId'] ?? null,
            parcelsCount: $data['parcelsCount'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->serviceId !== null) $out['serviceId'] = $this->serviceId;
        if ($this->parcelsCount !== null) $out['parcelsCount'] = $this->parcelsCount;
        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class SpecialDeliveryRequirements
{
    public function __construct(
        public readonly ?bool $requiredForAllShipments = null,
        public readonly ?array $requirements = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            requiredForAllShipments: $data['requiredForAllShipments'] ?? null,
            requirements: isset($data['requirements']) && is_array($data['requirements']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\Requirement::fromArray($r), $data['requirements']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->requiredForAllShipments !== null) $out['requiredForAllShipments'] = $this->requiredForAllShipments;
        if ($this->requirements !== null) $out['requirements'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\Requirement $x) => $x->toArray(), $this->requirements);
        return $out;
    }
}
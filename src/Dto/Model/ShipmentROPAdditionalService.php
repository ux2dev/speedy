<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentROPAdditionalService
{
    public function __construct(
        public readonly ?array $pallets = null,
        public readonly ?bool $thirdPartyPayer = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            pallets: isset($data['pallets']) && is_array($data['pallets']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\ShipmentROPAdditionalServiceLine::fromArray($r), $data['pallets']) : null,
            thirdPartyPayer: $data['thirdPartyPayer'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->pallets !== null) $out['pallets'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\ShipmentROPAdditionalServiceLine $x) => $x->toArray(), $this->pallets);
        if ($this->thirdPartyPayer !== null) $out['thirdPartyPayer'] = $this->thirdPartyPayer;
        return $out;
    }
}
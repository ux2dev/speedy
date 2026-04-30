<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentDiscountCardId
{
    public function __construct(
        public readonly ?int $contractId = null,
        public readonly ?int $cardId = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            contractId: $data['contractId'] ?? null,
            cardId: $data['cardId'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->contractId !== null) $out['contractId'] = $this->contractId;
        if ($this->cardId !== null) $out['cardId'] = $this->cardId;
        return $out;
    }
}
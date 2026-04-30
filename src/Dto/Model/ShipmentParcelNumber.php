<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ShipmentParcelNumber
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?int $seqNo = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            seqNo: $data['seqNo'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->seqNo !== null) $out['seqNo'] = $this->seqNo;
        return $out;
    }
}
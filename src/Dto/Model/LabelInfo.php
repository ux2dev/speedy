<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class LabelInfo
{
    public function __construct(
        public readonly ?string $parcelId = null,
        public readonly ?int $hubId = null,
        public readonly ?int $officeId = null,
        public readonly ?int $deadlineDay = null,
        public readonly ?int $deadlineMonth = null,
        public readonly ?int $tourId = null,
        public readonly ?string $fullBarcode = null,
        public readonly ?string $officeName = null,
        public readonly ?int $exportPriority = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            parcelId: $data['parcelId'] ?? null,
            hubId: $data['hubId'] ?? null,
            officeId: $data['officeId'] ?? null,
            deadlineDay: $data['deadlineDay'] ?? null,
            deadlineMonth: $data['deadlineMonth'] ?? null,
            tourId: $data['tourId'] ?? null,
            fullBarcode: $data['fullBarcode'] ?? null,
            officeName: $data['officeName'] ?? null,
            exportPriority: $data['exportPriority'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->parcelId !== null) $out['parcelId'] = $this->parcelId;
        if ($this->hubId !== null) $out['hubId'] = $this->hubId;
        if ($this->officeId !== null) $out['officeId'] = $this->officeId;
        if ($this->deadlineDay !== null) $out['deadlineDay'] = $this->deadlineDay;
        if ($this->deadlineMonth !== null) $out['deadlineMonth'] = $this->deadlineMonth;
        if ($this->tourId !== null) $out['tourId'] = $this->tourId;
        if ($this->fullBarcode !== null) $out['fullBarcode'] = $this->fullBarcode;
        if ($this->officeName !== null) $out['officeName'] = $this->officeName;
        if ($this->exportPriority !== null) $out['exportPriority'] = $this->exportPriority;
        return $out;
    }
}
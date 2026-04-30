<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class OfficeWorkingTimeSchedule
{
    public function __construct(
        public readonly ?string $date = null,
        public readonly ?string $workingTimeFrom = null,
        public readonly ?string $workingTimeTo = null,
        public readonly ?string $sameDayDepartureCutoff = null,
        public readonly ?bool $standardSchedule = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            date: $data['date'] ?? null,
            workingTimeFrom: $data['workingTimeFrom'] ?? null,
            workingTimeTo: $data['workingTimeTo'] ?? null,
            sameDayDepartureCutoff: $data['sameDayDepartureCutoff'] ?? null,
            standardSchedule: $data['standardSchedule'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->date !== null) $out['date'] = $this->date;
        if ($this->workingTimeFrom !== null) $out['workingTimeFrom'] = $this->workingTimeFrom;
        if ($this->workingTimeTo !== null) $out['workingTimeTo'] = $this->workingTimeTo;
        if ($this->sameDayDepartureCutoff !== null) $out['sameDayDepartureCutoff'] = $this->sameDayDepartureCutoff;
        if ($this->standardSchedule !== null) $out['standardSchedule'] = $this->standardSchedule;
        return $out;
    }
}
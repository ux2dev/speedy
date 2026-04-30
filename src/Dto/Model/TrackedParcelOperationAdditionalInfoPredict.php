<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class TrackedParcelOperationAdditionalInfoPredict
{
    public function __construct(
        public readonly ?string $predictedVisitDateTimeFrom = null,
        public readonly ?string $predictedVisitDateTimeTo = null,
        public readonly ?int $includedDelayInMinutes = null,
        public readonly ?bool $canceled = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            predictedVisitDateTimeFrom: $data['predictedVisitDateTimeFrom'] ?? null,
            predictedVisitDateTimeTo: $data['predictedVisitDateTimeTo'] ?? null,
            includedDelayInMinutes: $data['includedDelayInMinutes'] ?? null,
            canceled: $data['canceled'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->predictedVisitDateTimeFrom !== null) $out['predictedVisitDateTimeFrom'] = $this->predictedVisitDateTimeFrom;
        if ($this->predictedVisitDateTimeTo !== null) $out['predictedVisitDateTimeTo'] = $this->predictedVisitDateTimeTo;
        if ($this->includedDelayInMinutes !== null) $out['includedDelayInMinutes'] = $this->includedDelayInMinutes;
        if ($this->canceled !== null) $out['canceled'] = $this->canceled;
        return $out;
    }
}
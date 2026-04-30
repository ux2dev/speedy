<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Payments;

final readonly class PayoutRequest
{
    public function __construct(
        public readonly ?int $fromDate = null,
        public readonly ?int $toDate = null,
        public readonly ?bool $includeDetails = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->fromDate !== null) $out['fromDate'] = $this->fromDate;
        if ($this->toDate !== null) $out['toDate'] = $this->toDate;
        if ($this->includeDetails !== null) $out['includeDetails'] = $this->includeDetails;
        return $out;
    }
}
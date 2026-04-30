<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Shipment;

final readonly class FindParcelsByRefRequest
{
    public function __construct(
        public readonly ?string $ref = null,
        public readonly ?int $searchInRef = null,
        public readonly ?bool $shipmentsOnly = null,
        public readonly ?bool $includeReturns = null,
        public readonly ?int $fromDateTime = null,
        public readonly ?int $toDateTime = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->ref !== null) $out['ref'] = $this->ref;
        if ($this->searchInRef !== null) $out['searchInRef'] = $this->searchInRef;
        if ($this->shipmentsOnly !== null) $out['shipmentsOnly'] = $this->shipmentsOnly;
        if ($this->includeReturns !== null) $out['includeReturns'] = $this->includeReturns;
        if ($this->fromDateTime !== null) $out['fromDateTime'] = $this->fromDateTime;
        if ($this->toDateTime !== null) $out['toDateTime'] = $this->toDateTime;
        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Services;

final readonly class ServicesRequest
{
    public function __construct(
        public readonly ?int $date = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->date !== null) $out['date'] = $this->date;
        return $out;
    }
}
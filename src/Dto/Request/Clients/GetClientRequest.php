<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Clients;

final readonly class GetClientRequest
{
    public function __construct(
        // (schema declared no request properties beyond auth fields)
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];

        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Clients;

final readonly class GetContactByExternalIdRequest
{
    public function __construct(
        public readonly ?string $secretKey = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->secretKey !== null) $out['secretKey'] = $this->secretKey;
        return $out;
    }
}
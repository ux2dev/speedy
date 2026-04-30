<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Location;

final class SearchAddressResponse
{
    public function __construct(
        public readonly ?array $addresses = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            addresses: isset($data['addresses']) && is_array($data['addresses']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\SearchAddress::fromArray($r), $data['addresses']) : null,
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
        );
    }
}
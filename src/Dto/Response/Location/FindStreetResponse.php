<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Location;

final class FindStreetResponse
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?array $streets = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            streets: isset($data['streets']) && is_array($data['streets']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\Street::fromArray($r), $data['streets']) : null,
        );
    }
}
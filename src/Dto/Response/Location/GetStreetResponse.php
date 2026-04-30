<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Location;

final class GetStreetResponse
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Street $street = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            street: isset($data['street']) && is_array($data['street']) ? \Ux2Dev\Speedy\Dto\Model\Street::fromArray($data['street']) : null,
        );
    }
}
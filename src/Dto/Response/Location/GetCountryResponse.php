<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Location;

final class GetCountryResponse
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Country $country = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            country: isset($data['country']) && is_array($data['country']) ? \Ux2Dev\Speedy\Dto\Model\Country::fromArray($data['country']) : null,
        );
    }
}
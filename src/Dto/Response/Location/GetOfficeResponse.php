<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Location;

final class GetOfficeResponse
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Office $office = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            office: isset($data['office']) && is_array($data['office']) ? \Ux2Dev\Speedy\Dto\Model\Office::fromArray($data['office']) : null,
        );
    }
}
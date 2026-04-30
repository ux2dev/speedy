<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Location;

final class FindOfficeResponse
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?array $offices = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            offices: isset($data['offices']) && is_array($data['offices']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\Office::fromArray($r), $data['offices']) : null,
        );
    }
}
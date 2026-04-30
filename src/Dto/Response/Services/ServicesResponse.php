<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Services;

final class ServicesResponse
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?array $services = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            services: isset($data['services']) && is_array($data['services']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\CourierService::fromArray($r), $data['services']) : null,
        );
    }
}
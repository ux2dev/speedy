<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Pickup;

final class PickupResponse
{
    public function __construct(
        public readonly ?array $orders = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            orders: isset($data['orders']) && is_array($data['orders']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\PickupOrder::fromArray($r), $data['orders']) : null,
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
        );
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class PaymentType
{
    public function __construct(
        // (schema declared no scalar properties)
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(

        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];

        return $out;
    }
}
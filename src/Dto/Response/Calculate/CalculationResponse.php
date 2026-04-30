<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Calculate;

final class CalculationResponse
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?array $calculations = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            calculations: isset($data['calculations']) && is_array($data['calculations']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\CalculationResult::fromArray($r), $data['calculations']) : null,
        );
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Validation;

final readonly class ValidatePhoneRequest
{
    public function __construct(
        public readonly ?string $number = null,
        public readonly ?string $ext = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->number !== null) $out['number'] = $this->number;
        if ($this->ext !== null) $out['ext'] = $this->ext;
        return $out;
    }
}
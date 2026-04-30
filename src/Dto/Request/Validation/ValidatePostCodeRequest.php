<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Validation;

final readonly class ValidatePostCodeRequest
{
    public function __construct(
        public readonly ?int $countryId = null,
        public readonly ?int $siteId = null,
        public readonly ?string $postCode = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->countryId !== null) $out['countryId'] = $this->countryId;
        if ($this->siteId !== null) $out['siteId'] = $this->siteId;
        if ($this->postCode !== null) $out['postCode'] = $this->postCode;
        return $out;
    }
}
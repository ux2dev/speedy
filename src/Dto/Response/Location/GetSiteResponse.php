<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Location;

final class GetSiteResponse
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Site $site = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            site: isset($data['site']) && is_array($data['site']) ? \Ux2Dev\Speedy\Dto\Model\Site::fromArray($data['site']) : null,
        );
    }
}
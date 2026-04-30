<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Clients;

final class GetClientResponse
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Client $client = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            client: isset($data['client']) && is_array($data['client']) ? \Ux2Dev\Speedy\Dto\Model\Client::fromArray($data['client']) : null,
        );
    }
}
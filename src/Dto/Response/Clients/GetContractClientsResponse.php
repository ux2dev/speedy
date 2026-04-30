<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Clients;

final class GetContractClientsResponse
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?array $clients = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            clients: isset($data['clients']) && is_array($data['clients']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\Client::fromArray($r), $data['clients']) : null,
        );
    }
}
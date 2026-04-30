<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Track;

final class TrackResponse
{
    public function __construct(
        public readonly ?array $parcels = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            parcels: isset($data['parcels']) && is_array($data['parcels']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\TrackedParcel::fromArray($r), $data['parcels']) : null,
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
        );
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Track;

final class BulkTrackingDataFilesResponse
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?array $files = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            files: isset($data['files']) && is_array($data['files']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\BulkTrackingDataFile::fromArray($r), $data['files']) : null,
        );
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Track;

final readonly class BulkTrackingDataFilesRequest
{
    public function __construct(
        public readonly ?int $lastProcessedFileId = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->lastProcessedFileId !== null) $out['lastProcessedFileId'] = $this->lastProcessedFileId;
        return $out;
    }
}
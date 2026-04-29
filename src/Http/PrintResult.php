<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Http;

use RuntimeException;

final class PrintResult
{
    public function __construct(
        public readonly string $body,
        public readonly string $contentType,
        public readonly ?string $filename = null,
    ) {
    }

    public function bytes(): string
    {
        return $this->body;
    }

    public function saveTo(string $path): int
    {
        $written = file_put_contents($path, $this->body);

        if ($written === false) {
            throw new RuntimeException("Failed to write PrintResult to {$path}");
        }

        return $written;
    }

    public function isPdf(): bool
    {
        return str_starts_with($this->contentType, 'application/pdf');
    }

    public function isZpl(): bool
    {
        return $this->contentType === 'text/plain'
            || str_starts_with($this->contentType, 'application/zpl')
            || str_starts_with($this->contentType, 'application/x-zpl');
    }
}

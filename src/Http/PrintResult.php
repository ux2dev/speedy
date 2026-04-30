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
        if (str_contains($path, "\0")) {
            throw new RuntimeException('PrintResult::saveTo refuses paths containing null bytes');
        }
        if (preg_match('~^[a-z][a-z0-9+.\-]*://~i', $path) === 1) {
            throw new RuntimeException(
                'PrintResult::saveTo refuses stream wrappers (e.g. phar://, http://, file://). '
                . 'Pass a plain filesystem path instead.'
            );
        }

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

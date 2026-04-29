<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Exception;

use Throwable;

final class ApiException extends SpeedyException
{
    /** @param array<string, mixed> $body */
    public function __construct(
        string $message,
        public readonly ?int $code = null,
        public readonly ?string $apiMessage = null,
        public readonly ?string $context = null,
        public readonly ?string $errorId = null,
        public readonly ?string $component = null,
        public readonly ?int $httpStatus = null,
        public readonly array $body = [],
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}

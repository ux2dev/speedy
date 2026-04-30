<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Laravel\Http;

use Psr\Http\Client\ClientExceptionInterface;
use RuntimeException;

final class HttpClientException extends RuntimeException implements ClientExceptionInterface
{
}

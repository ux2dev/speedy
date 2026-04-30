<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Laravel\Http;

use Illuminate\Http\Client\Factory as LaravelHttpFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * PSR-18 adapter that dispatches PSR-7 requests through Laravel's Http facade
 * (Illuminate\Http\Client\Factory). Used in place of a direct Guzzle client so
 * applications can mock requests with Http::fake() and observe them through
 * Laravel's standard tooling.
 */
final class LaravelHttpClient implements ClientInterface
{
    public function __construct(
        private readonly LaravelHttpFactory $factory,
        private readonly int $timeoutSeconds = 30,
    ) {
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $contentType = $request->getHeaderLine('Content-Type') ?: 'application/octet-stream';

        $headers = [];
        foreach ($request->getHeaders() as $name => $values) {
            if (strcasecmp($name, 'Content-Type') === 0) {
                continue;
            }
            $headers[$name] = implode(', ', $values);
        }

        $pending = $this->factory
            ->timeout($this->timeoutSeconds)
            ->withHeaders($headers);

        $body = (string) $request->getBody();
        if ($body !== '') {
            $pending = $pending->withBody($body, $contentType);
        }

        try {
            $response = $pending->send($request->getMethod(), (string) $request->getUri());
        } catch (Throwable $e) {
            throw new HttpClientException('HTTP transport error: ' . $e->getMessage(), 0, $e);
        }

        return $response->toPsrResponse();
    }
}

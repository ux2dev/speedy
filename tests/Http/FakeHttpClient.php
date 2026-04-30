<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Tests\Http;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Programmable PSR-18 client. Records every outgoing request and returns
 * canned responses in FIFO order.
 */
final class FakeHttpClient implements ClientInterface
{
    /** @var list<RequestInterface> */
    public array $requests = [];

    /** @var list<ResponseInterface> */
    private array $responses = [];

    /** @var array<int, \Throwable> */
    private array $exceptions = [];

    /** @param array<string, string> $headers */
    public function queueJson(int $status, array $body, array $headers = []): void
    {
        $this->responses[] = new Response(
            $status,
            $headers + ['Content-Type' => 'application/json'],
            json_encode($body, JSON_THROW_ON_ERROR),
        );
    }

    /** @param array<string, string> $headers */
    public function queueBinary(int $status, string $body, string $contentType, array $headers = []): void
    {
        $this->responses[] = new Response(
            $status,
            $headers + ['Content-Type' => $contentType],
            $body,
        );
    }

    public function queueRaw(ResponseInterface $response): void
    {
        $this->responses[] = $response;
    }

    public function queueException(\Throwable $e): void
    {
        $this->exceptions[count($this->responses)] = $e;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $idx = count($this->requests);
        $this->requests[] = $request;

        if (isset($this->exceptions[$idx])) {
            throw $this->exceptions[$idx];
        }

        if (! isset($this->responses[$idx])) {
            throw new \LogicException('FakeHttpClient ran out of queued responses');
        }

        return $this->responses[$idx];
    }

    /** @return array<string, mixed> */
    public function lastRequestBody(): array
    {
        $last = end($this->requests);
        if ($last === false) {
            throw new \LogicException('No requests recorded');
        }
        $body = (string) $last->getBody();
        $decoded = json_decode($body, true, flags: JSON_THROW_ON_ERROR);
        if (! is_array($decoded)) {
            throw new \LogicException('Last request body was not a JSON object');
        }
        return $decoded;
    }
}

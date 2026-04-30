<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Http;

use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Ux2Dev\Speedy\Config\SpeedyConfig;
use Ux2Dev\Speedy\Exception\ApiException;
use Ux2Dev\Speedy\Exception\InvalidResponseException;
use Ux2Dev\Speedy\Exception\TransportException;

final class SpeedyTransport
{
    public function __construct(
        public readonly SpeedyConfig $config,
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
    ) {
    }

    /**
     * @template T of object
     * @param  array<string, mixed> $body
     * @param  class-string<T>      $responseClass  must expose static fromArray(array): self
     * @return T
     */
    public function postJson(string $path, array $body, string $responseClass): object
    {
        return $this->sendJson('POST', $path, $body, $responseClass);
    }

    /**
     * @template T of object
     * @param  array<string, mixed> $body
     * @param  class-string<T>      $responseClass
     * @return T
     */
    public function getJson(string $path, array $body, string $responseClass): object
    {
        return $this->sendJson('GET', $path, $body, $responseClass);
    }

    /**
     * @template T of object
     * @param  array<string, mixed> $body
     * @param  class-string<T>      $responseClass
     * @return T
     */
    public function deleteJson(string $path, array $body, string $responseClass): object
    {
        return $this->sendJson('DELETE', $path, $body, $responseClass);
    }

    /** @param array<string, mixed> $body */
    public function postBinary(string $path, array $body): PrintResult
    {
        $response = $this->dispatch('POST', $path, $body);
        $status   = $response->getStatusCode();
        $raw      = (string) $response->getBody();

        if ($raw === '') {
            throw new InvalidResponseException("Empty binary response body (HTTP {$status})");
        }

        $contentType = $response->getHeaderLine('Content-Type') ?: 'application/octet-stream';
        $disposition = $response->getHeaderLine('Content-Disposition');
        $filename    = null;
        if ($disposition !== '' && preg_match('~filename="?([^";]+)"?~', $disposition, $m)) {
            $filename = $m[1];
        }

        return new PrintResult($raw, $contentType, $filename);
    }

    /**
     * @template T of object
     * @param  array<string, mixed> $body
     * @param  class-string<T>      $responseClass
     * @return T
     */
    private function sendJson(string $method, string $path, array $body, string $responseClass): object
    {
        $response = $this->dispatch($method, $path, $body);
        $status   = $response->getStatusCode();
        $raw      = (string) $response->getBody();

        if ($raw === '') {
            throw new InvalidResponseException("Empty response body (HTTP {$status})");
        }

        try {
            $decoded = json_decode($raw, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new InvalidResponseException(
                "Malformed JSON response (HTTP {$status}): " . $e->getMessage(),
                previous: $e,
            );
        }

        if (! is_array($decoded)) {
            throw new InvalidResponseException('Expected JSON object, got ' . gettype($decoded));
        }

        if (isset($decoded['error']) && is_array($decoded['error']) && $decoded['error'] !== []) {
            $err = $decoded['error'];
            throw new ApiException(
                'Speedy API error: ' . ($err['message'] ?? 'unknown'),
                apiCode: isset($err['code']) ? (int) $err['code'] : null,
                apiMessage: isset($err['message']) ? (string) $err['message'] : null,
                context: isset($err['context']) ? (string) $err['context'] : null,
                errorId: isset($err['id']) ? (string) $err['id'] : null,
                component: isset($err['component']) ? (string) $err['component'] : null,
                httpStatus: $status,
                body: $decoded,
            );
        }

        return $responseClass::fromArray($decoded);
    }

    /** @param array<string, mixed> $body */
    private function dispatch(string $method, string $path, array $body): ResponseInterface
    {
        $merged = $this->mergeAuth($body);

        try {
            $json = json_encode($merged, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (JsonException $e) {
            throw new InvalidResponseException('Failed to encode request body: ' . $e->getMessage(), previous: $e);
        }

        $url = $this->config->baseUrl . ltrim($path, '/');

        $request = $this->requestFactory->createRequest($method, $url)
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withHeader('Accept', 'application/json')
            ->withBody($this->streamFactory->createStream($json));

        try {
            return $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new TransportException('HTTP transport error: ' . $e->getMessage(), previous: $e);
        }
    }

    /**
     * @param  array<string, mixed> $body
     * @return array<string, mixed>
     */
    private function mergeAuth(array $body): array
    {
        $body['userName'] = $body['userName'] ?? $this->config->userName;
        $body['password'] = $body['password'] ?? $this->config->getPassword();

        if (! array_key_exists('language', $body) && $this->config->language !== null) {
            $body['language'] = $this->config->language;
        }
        if (! array_key_exists('clientSystemId', $body) && $this->config->clientSystemId !== null) {
            $body['clientSystemId'] = $this->config->clientSystemId;
        }

        return $body;
    }
}

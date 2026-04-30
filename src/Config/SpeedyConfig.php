<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Config;

use LogicException;
use Ux2Dev\Speedy\Exception\ConfigurationException;

final readonly class SpeedyConfig
{
    public const DEFAULT_ALLOWED_HOSTS = ['api.speedy.bg'];

    public string $baseUrl;

    /**
     * @param list<string> $additionalAllowedHosts Extra hostnames to accept beyond
     *                                             api.speedy.bg + local dev hosts.
     */
    public function __construct(
        string $baseUrl = 'https://api.speedy.bg/v1',
        public string $userName = '',
        private string $password = '',
        public ?string $language = null,
        public ?int $clientSystemId = null,
        public int $timeout = 30,
        array $additionalAllowedHosts = [],
    ) {
        if ($baseUrl === '') {
            throw new ConfigurationException('baseUrl must not be empty');
        }
        if ($userName === '') {
            throw new ConfigurationException('userName must not be empty');
        }
        if ($password === '') {
            throw new ConfigurationException('password must not be empty');
        }
        if ($timeout < 1) {
            throw new ConfigurationException('timeout must be at least 1 second');
        }
        if ($clientSystemId !== null && $clientSystemId < 1) {
            throw new ConfigurationException('clientSystemId must be a positive integer when provided');
        }

        $parts = parse_url($baseUrl);
        if ($parts === false || ! isset($parts['scheme'], $parts['host'])) {
            throw new ConfigurationException('baseUrl must be a valid absolute URL');
        }

        $scheme = strtolower($parts['scheme']);
        $host   = strtolower($parts['host']);

        if ($scheme !== 'http' && $scheme !== 'https') {
            throw new ConfigurationException('baseUrl scheme must be http or https');
        }

        $isLocalDev = self::isLocalDevHost($host);

        if ($scheme === 'http' && ! $isLocalDev) {
            throw new ConfigurationException(
                'baseUrl must use https:// (http:// is allowed only for localhost/127.0.0.1/::1/.test/.local hosts)'
            );
        }

        if (! $isLocalDev) {
            $allowed = array_merge(self::DEFAULT_ALLOWED_HOSTS, array_map('strtolower', $additionalAllowedHosts));
            if (! in_array($host, $allowed, true)) {
                throw new ConfigurationException(
                    'baseUrl host "' . $host . '" is not in the allowlist. '
                    . 'Pass it via the $additionalAllowedHosts constructor parameter if intended.'
                );
            }
        }

        $this->baseUrl = rtrim($baseUrl, '/') . '/';
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /** @return array<string, mixed> */
    public function __debugInfo(): array
    {
        return [
            'baseUrl'        => $this->baseUrl,
            'userName'       => $this->userName,
            'password'       => $this->password !== '' ? '[REDACTED]' : '',
            'language'       => $this->language,
            'clientSystemId' => $this->clientSystemId,
            'timeout'        => $this->timeout,
        ];
    }

    /** @return array<int|string, mixed> */
    public function __serialize(): array
    {
        throw new LogicException('SpeedyConfig must not be serialized as it contains a password');
    }

    /** @param array<int|string, mixed> $data */
    public function __unserialize(array $data): void
    {
        throw new LogicException('SpeedyConfig must not be unserialized');
    }

    private static function isLocalDevHost(string $host): bool
    {
        return $host === 'localhost'
            || $host === '127.0.0.1'
            || $host === '::1'
            || str_ends_with($host, '.test')
            || str_ends_with($host, '.local');
    }
}

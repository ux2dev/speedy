<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Config;

use LogicException;
use Ux2Dev\Speedy\Exception\ConfigurationException;

final readonly class SpeedyConfig
{
    public string $baseUrl;

    public function __construct(
        string $baseUrl = 'https://api.speedy.bg/v1',
        public string $userName = '',
        private string $password = '',
        public ?string $language = null,
        public ?int $clientSystemId = null,
        public int $timeout = 30,
    ) {
        if ($baseUrl === '') {
            throw new ConfigurationException('baseUrl must not be empty');
        }
        if (! preg_match('~^https?://~i', $baseUrl)) {
            throw new ConfigurationException('baseUrl must start with http:// or https://');
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
}

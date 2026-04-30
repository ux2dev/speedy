<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Laravel;

use Illuminate\Http\Client\Factory as LaravelHttpFactory;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Ux2Dev\Speedy\Config\SpeedyConfig;
use Ux2Dev\Speedy\Exception\ConfigurationException;
use Ux2Dev\Speedy\Laravel\Http\LaravelHttpClient;
use Ux2Dev\Speedy\Speedy;

final class SpeedyManager
{
    /** @var array<string, Speedy> */
    private array $instances = [];

    private string $currentAccount;

    /** @param array<string, mixed> $config */
    public function __construct(
        private readonly array $config,
        private readonly LaravelHttpFactory $httpFactory,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
    ) {
        $this->currentAccount = (string) ($config['default'] ?? 'main');
    }

    public function account(string $name): self
    {
        $clone = clone $this;
        $clone->currentAccount = $name;
        return $clone;
    }

    public function currentAccount(): string
    {
        return $this->currentAccount;
    }

    public function instance(): Speedy
    {
        return $this->instances[$this->currentAccount] ??= $this->build($this->currentAccount);
    }

    /** @param array<int, mixed> $arguments */
    public function __call(string $method, array $arguments): mixed
    {
        return $this->instance()->{$method}(...$arguments);
    }

    private function build(string $account): Speedy
    {
        $accounts = (array) ($this->config['accounts'] ?? []);

        if (! isset($accounts[$account]) || ! is_array($accounts[$account])) {
            throw new ConfigurationException("Speedy account \"{$account}\" is not configured");
        }

        $c = $accounts[$account];

        $additionalAllowedHosts = (array) ($c['additional_allowed_hosts'] ?? []);

        $config = new SpeedyConfig(
            baseUrl:                 (string) ($c['base_url'] ?? 'https://api.speedy.bg/v1'),
            userName:                (string) ($c['user_name'] ?? ''),
            password:                (string) ($c['password'] ?? ''),
            language:                isset($c['language']) ? (string) $c['language'] : null,
            clientSystemId:          isset($c['client_system_id']) ? (int) $c['client_system_id'] : null,
            timeout:                 (int) ($c['timeout'] ?? 30),
            additionalAllowedHosts:  array_values(array_map('strval', $additionalAllowedHosts)),
        );

        return new Speedy(
            $config,
            new LaravelHttpClient($this->httpFactory, $config->timeout),
            $this->requestFactory,
            $this->streamFactory,
        );
    }
}

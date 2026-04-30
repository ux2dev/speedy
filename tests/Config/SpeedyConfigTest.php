<?php

declare(strict_types=1);

use Ux2Dev\Speedy\Config\SpeedyConfig;
use Ux2Dev\Speedy\Exception\ConfigurationException;

it('accepts a fully populated config', function () {
    $config = new SpeedyConfig(
        baseUrl: 'https://api.speedy.bg/v1',
        userName: 'demo',
        password: 'secret',
        language: 'EN',
        clientSystemId: 42,
        timeout: 60,
    );

    expect($config->baseUrl)->toBe('https://api.speedy.bg/v1/');
    expect($config->userName)->toBe('demo');
    expect($config->getPassword())->toBe('secret');
    expect($config->language)->toBe('EN');
    expect($config->clientSystemId)->toBe(42);
    expect($config->timeout)->toBe(60);
});

it('uses sensible defaults', function () {
    $config = new SpeedyConfig(userName: 'demo', password: 'secret');

    expect($config->baseUrl)->toBe('https://api.speedy.bg/v1/');
    expect($config->language)->toBeNull();
    expect($config->clientSystemId)->toBeNull();
    expect($config->timeout)->toBe(30);
});

it('rejects empty userName', function () {
    new SpeedyConfig(userName: '', password: 'secret');
})->throws(ConfigurationException::class, 'userName must not be empty');

it('rejects empty password', function () {
    new SpeedyConfig(userName: 'demo', password: '');
})->throws(ConfigurationException::class, 'password must not be empty');

it('rejects unsupported scheme', function () {
    new SpeedyConfig(baseUrl: 'ftp://api.speedy.bg/v1', userName: 'demo', password: 'secret');
})->throws(ConfigurationException::class, 'baseUrl scheme must be http or https');

it('rejects http:// against the public Speedy host', function () {
    new SpeedyConfig(baseUrl: 'http://api.speedy.bg/v1', userName: 'demo', password: 'secret');
})->throws(ConfigurationException::class, 'baseUrl must use https');

it('allows http:// against localhost for local development', function () {
    $config = new SpeedyConfig(baseUrl: 'http://localhost:8080/v1', userName: 'demo', password: 'secret');
    expect($config->baseUrl)->toBe('http://localhost:8080/v1/');
});

it('allows http:// against *.test hosts for local development', function () {
    $config = new SpeedyConfig(baseUrl: 'http://speedy.test/v1', userName: 'demo', password: 'secret');
    expect($config->baseUrl)->toBe('http://speedy.test/v1/');
});

it('rejects baseUrl with a non-allowlisted host', function () {
    new SpeedyConfig(baseUrl: 'https://attacker.example.com/v1', userName: 'demo', password: 'secret');
})->throws(ConfigurationException::class, 'is not in the allowlist');

it('accepts baseUrl whose host is passed via additionalAllowedHosts', function () {
    $config = new SpeedyConfig(
        baseUrl: 'https://sandbox.speedy.partner.example/v1',
        userName: 'demo',
        password: 'secret',
        additionalAllowedHosts: ['sandbox.speedy.partner.example'],
    );
    expect($config->baseUrl)->toBe('https://sandbox.speedy.partner.example/v1/');
});

it('rejects empty baseUrl', function () {
    new SpeedyConfig(baseUrl: '', userName: 'demo', password: 'secret');
})->throws(ConfigurationException::class, 'baseUrl must not be empty');

it('rejects baseUrl that is not a valid absolute URL', function () {
    // No host — parse_url returns ['scheme' => 'http'] but isset()['host'] is false.
    new SpeedyConfig(baseUrl: 'http:///path-only', userName: 'demo', password: 'secret');
})->throws(ConfigurationException::class, 'baseUrl must be a valid absolute URL');

it('rejects timeout below 1', function () {
    new SpeedyConfig(userName: 'demo', password: 'secret', timeout: 0);
})->throws(ConfigurationException::class, 'timeout must be at least 1 second');

it('rejects non-positive clientSystemId', function () {
    new SpeedyConfig(userName: 'demo', password: 'secret', clientSystemId: 0);
})->throws(ConfigurationException::class, 'clientSystemId must be a positive integer');

it('rejects negative clientSystemId', function () {
    new SpeedyConfig(userName: 'demo', password: 'secret', clientSystemId: -3);
})->throws(ConfigurationException::class, 'clientSystemId must be a positive integer');

it('redacts password in __debugInfo', function () {
    $config = new SpeedyConfig(userName: 'demo', password: 'secret');

    $info = $config->__debugInfo();
    expect($info['password'])->toBe('[REDACTED]');
    expect($info['userName'])->toBe('demo');
});

it('blocks serialization', function () {
    $config = new SpeedyConfig(userName: 'demo', password: 'secret');

    expect(fn () => serialize($config))->toThrow(LogicException::class);
});

it('blocks unserialization', function () {
    $config = new SpeedyConfig(userName: 'demo', password: 'secret');

    expect(fn () => $config->__unserialize([]))->toThrow(LogicException::class);
});

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

it('rejects malformed baseUrl', function () {
    new SpeedyConfig(baseUrl: 'ftp://nope', userName: 'demo', password: 'secret');
})->throws(ConfigurationException::class, 'baseUrl must start with http:// or https://');

it('rejects empty baseUrl', function () {
    new SpeedyConfig(baseUrl: '', userName: 'demo', password: 'secret');
})->throws(ConfigurationException::class, 'baseUrl must not be empty');

it('rejects timeout below 1', function () {
    new SpeedyConfig(userName: 'demo', password: 'secret', timeout: 0);
})->throws(ConfigurationException::class, 'timeout must be at least 1 second');

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

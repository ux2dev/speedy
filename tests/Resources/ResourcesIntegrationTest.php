<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\HttpFactory;
use Ux2Dev\Speedy\Config\SpeedyConfig;
use Ux2Dev\Speedy\Speedy;
use Ux2Dev\Speedy\Tests\Http\FakeHttpClient;

it('every catalog operation issues the right request and returns the typed response', function () {
    $catalog = json_decode(
        (string) file_get_contents(__DIR__ . '/../../bin/endpoints.json'),
        true,
        flags: JSON_THROW_ON_ERROR,
    );
    expect($catalog)->toBeArray()->not->toBeEmpty();

    foreach ($catalog as $entry) {
        $group    = $entry['group'];
        $name     = $entry['name'];
        $path     = $entry['path'];
        $method   = strtoupper($entry['method']);
        $returns  = $entry['returns'] ?? 'json';
        $accessor = $group === 'PrintService' ? 'print' : lcfirst($group);

        $client = new FakeHttpClient();
        match ($returns) {
            'bytes' => $client->queueBinary(200, '%PDF-1.4 fake', 'application/pdf'),
            'csv'   => $client->queueBinary(200, "id,name\n1,foo\n", 'text/csv;charset=UTF-8'),
            default => $client->queueJson(200, []),
        };
        $factory = new HttpFactory();
        $config  = new SpeedyConfig(userName: 'demo', password: 'secret', language: 'EN');
        $speedy  = new Speedy($config, $client, $factory, $factory);

        $resource = $speedy->{$accessor}();

        if ($returns === 'csv') {
            // CSV bulk endpoints take positional path params (one int each in
            // the catalog so far — generalises if the schema grows).
            $args        = array_map(fn() => 100, $entry['pathParams'] ?? []);
            $expectedUri = 'https://api.speedy.bg/v1' . $path . ($args === [] ? '' : '/' . implode('/', $args));
            $result      = $resource->{$name}(...$args);
        } else {
            $reqClass    = 'Ux2Dev\\Speedy\\Dto\\Request\\' . $group . '\\' . $entry['request'];
            $request     = new $reqClass();
            $result      = $resource->{$name}($request);
            $expectedUri = 'https://api.speedy.bg/v1' . $path;
        }

        expect($client->requests)->toHaveCount(1, "no request issued for {$group}::{$name}");
        expect((string) $client->requests[0]->getUri())->toBe($expectedUri, "wrong URI for {$group}::{$name}");
        expect($client->requests[0]->getMethod())->toBe($method, "wrong method for {$group}::{$name}");

        $body = json_decode((string) $client->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);
        expect($body['userName'])->toBe('demo', "auth not injected for {$group}::{$name}");
        expect($body['password'])->toBe('secret', "auth not injected for {$group}::{$name}");
        expect($body['language'])->toBe('EN', "language default not injected for {$group}::{$name}");

        match ($returns) {
            'json'  => expect($result)->toBeInstanceOf('Ux2Dev\\Speedy\\Dto\\Response\\' . $group . '\\' . $entry['response']),
            'bytes' => expect($result)->toBeInstanceOf(\Ux2Dev\Speedy\Http\PrintResult::class),
            'csv'   => expect($result)->toBeString(),
        };
    }
});

it('per-call language override wins over config default', function () {
    $client  = new FakeHttpClient();
    $client->queueJson(200, []);
    $factory = new HttpFactory();
    $config  = new SpeedyConfig(userName: 'u', password: 'p', language: 'EN');
    $speedy  = new Speedy($config, $client, $factory, $factory);

    $speedy->shipment()->create(
        new \Ux2Dev\Speedy\Dto\Request\Shipment\CreateShipmentRequest(),
        language: 'BG',
    );

    $body = json_decode((string) $client->requests[0]->getBody(), true);
    expect($body['language'])->toBe('BG');
});

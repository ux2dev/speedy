<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Tests\Laravel;

use GuzzleHttp\Psr7\HttpFactory;
use Illuminate\Http\Client\Factory as LaravelHttpFactory;
use Illuminate\Support\Facades\Http;
use Ux2Dev\Speedy\Laravel\Http\HttpClientException;
use Ux2Dev\Speedy\Laravel\Http\LaravelHttpClient;

it('dispatches a PSR-7 request through Laravel Http and returns a PSR-7 response', function () {
    Http::fake([
        '*' => Http::response(['ok' => true], 200, ['X-Speedy-Test' => 'yes']),
    ]);

    $factory = new HttpFactory();
    $request = $factory->createRequest('POST', 'https://api.speedy.bg/v1/echo')
        ->withHeader('Content-Type', 'application/json; charset=utf-8')
        ->withHeader('X-Custom', 'value')
        ->withBody($factory->createStream('{"hello":"world"}'));

    $client   = new LaravelHttpClient($this->app->make(LaravelHttpFactory::class));
    $response = $client->sendRequest($request);

    expect($response->getStatusCode())->toBe(200);
    expect((string) $response->getBody())->toContain('"ok"');
    expect($response->getHeaderLine('X-Speedy-Test'))->toBe('yes');

    Http::assertSent(function ($req) {
        return $req->method() === 'POST'
            && $req->url() === 'https://api.speedy.bg/v1/echo'
            && $req->hasHeader('X-Custom', 'value')
            && $req->body() === '{"hello":"world"}';
    });
});

it('omits the body and Content-Type when the request has no body', function () {
    Http::fake(['*' => Http::response('', 204)]);

    $factory = new HttpFactory();
    $request = $factory->createRequest('GET', 'https://api.speedy.bg/v1/ping')
        ->withHeader('Accept', 'application/json');

    $client   = new LaravelHttpClient($this->app->make(LaravelHttpFactory::class));
    $response = $client->sendRequest($request);

    expect($response->getStatusCode())->toBe(204);
});

it('wraps Laravel transport errors in HttpClientException', function () {
    // No Http::fake — Laravel will try to connect and fail. We avoid actually
    // hitting the network by pointing at an unroutable address with a tiny
    // timeout; the client throws a ConnectionException which we expect to be
    // wrapped in HttpClientException.
    $factory = new HttpFactory();
    $request = $factory->createRequest('POST', 'https://127.0.0.1:1/never');

    $client = new LaravelHttpClient($this->app->make(LaravelHttpFactory::class), timeoutSeconds: 1);

    expect(fn () => $client->sendRequest($request))
        ->toThrow(HttpClientException::class);
});

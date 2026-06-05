<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use Ux2Dev\Speedy\Config\SpeedyConfig;
use Ux2Dev\Speedy\Exception\ApiException;
use Ux2Dev\Speedy\Exception\InvalidResponseException;
use Ux2Dev\Speedy\Exception\TransportException;
use Ux2Dev\Speedy\Http\PrintResult;
use Ux2Dev\Speedy\Http\SpeedyTransport;
use Ux2Dev\Speedy\Tests\Http\FakeHttpClient;

final class FakeResponse
{
    /** @param array<string, mixed> $data */
    public function __construct(public array $data = []) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}

function makeTransport(FakeHttpClient $client, ?string $defaultLanguage = 'EN', ?int $defaultClientSystemId = null): SpeedyTransport
{
    $config = new SpeedyConfig(
        userName: 'demo',
        password: 'secret',
        language: $defaultLanguage,
        clientSystemId: $defaultClientSystemId,
    );
    $factory = new HttpFactory();
    return new SpeedyTransport($config, $client, $factory, $factory);
}

it('postJson auto-injects credentials and language', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, ['hello' => 'world']);

    makeTransport($client)->postJson('/shipment', ['ref1' => 'ABC'], FakeResponse::class);

    $body = $client->lastRequestBody();
    expect($body)->toMatchArray([
        'userName' => 'demo',
        'password' => 'secret',
        'language' => 'EN',
        'ref1'     => 'ABC',
    ]);
    expect((string) $client->requests[0]->getUri())->toBe('https://api.speedy.bg/v1/shipment');
    expect($client->requests[0]->getMethod())->toBe('POST');
});

it('per-call body fields override config defaults', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, []);

    makeTransport($client, defaultLanguage: 'EN')
        ->postJson('/shipment', ['language' => 'BG'], FakeResponse::class);

    expect($client->lastRequestBody()['language'])->toBe('BG');
});

it('does not inject clientSystemId when not configured and not in body', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, []);

    makeTransport($client, defaultClientSystemId: null)
        ->postJson('/shipment', [], FakeResponse::class);

    expect($client->lastRequestBody())->not->toHaveKey('clientSystemId');
});

it('returns the typed response DTO via fromArray', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, ['ok' => true]);

    $result = makeTransport($client)->postJson('/x', [], FakeResponse::class);

    expect($result)->toBeInstanceOf(FakeResponse::class);
    expect($result->data)->toBe(['ok' => true]);
});

it('throws ApiException when response carries a non-null error', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, [
        'error' => ['code' => 1234, 'message' => 'No no', 'context' => 'shipment', 'id' => 'X', 'component' => 'svc'],
    ]);

    expect(fn () => makeTransport($client)->postJson('/x', [], FakeResponse::class))
        ->toThrow(ApiException::class);
});

it('exposes structured error fields on ApiException', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, [
        'error' => ['code' => 1234, 'message' => 'No no', 'context' => 'ctx', 'id' => 'ID', 'component' => 'cmp'],
    ]);

    try {
        makeTransport($client)->postJson('/x', [], FakeResponse::class);
        $this->fail('ApiException not thrown');
    } catch (ApiException $e) {
        expect($e->apiCode)->toBe(1234);
        expect($e->apiMessage)->toBe('No no');
        expect($e->context)->toBe('ctx');
        expect($e->errorId)->toBe('ID');
        expect($e->component)->toBe('cmp');
        expect($e->httpStatus)->toBe(200);
    }
});

it('throws TransportException on PSR-18 client failure', function () {
    $client = new FakeHttpClient();
    $client->queueException(new class extends \Exception implements \Psr\Http\Client\ClientExceptionInterface {});

    expect(fn () => makeTransport($client)->postJson('/x', [], FakeResponse::class))
        ->toThrow(TransportException::class);
});

it('throws InvalidResponseException on empty body', function () {
    $client = new FakeHttpClient();
    $client->queueRaw(new Response(200, [], ''));

    expect(fn () => makeTransport($client)->postJson('/x', [], FakeResponse::class))
        ->toThrow(InvalidResponseException::class);
});

it('throws InvalidResponseException on malformed JSON', function () {
    $client = new FakeHttpClient();
    $client->queueRaw(new Response(200, [], '{not json'));

    expect(fn () => makeTransport($client)->postJson('/x', [], FakeResponse::class))
        ->toThrow(InvalidResponseException::class);
});

it('postBinary returns PrintResult with body and Content-Type', function () {
    $client = new FakeHttpClient();
    $client->queueBinary(200, '%PDF-1.4 fake', 'application/pdf', [
        'Content-Disposition' => 'attachment; filename="voucher.pdf"',
    ]);

    $result = makeTransport($client)->postBinary('/print', ['barcodes' => ['X']]);

    expect($result)->toBeInstanceOf(PrintResult::class);
    expect($result->body)->toBe('%PDF-1.4 fake');
    expect($result->contentType)->toBe('application/pdf');
    expect($result->filename)->toBe('voucher.pdf');
});

it('postBinary leaves filename null when no Content-Disposition', function () {
    $client = new FakeHttpClient();
    $client->queueBinary(200, '%PDF-1.4 fake', 'application/pdf');

    $result = makeTransport($client)->postBinary('/print', []);

    expect($result->filename)->toBeNull();
});

it('postBinary surfaces JSON {error} envelopes as ApiException', function () {
    $client = new FakeHttpClient();
    // Speedy returns HTTP 200 + application/json on print failure (e.g. a body
    // without `parcels`) instead of a binary label.
    $client->queueJson(200, ['error' => [
        'code'    => 1,
        'message' => 'System error: getParcels() is null',
        'id'      => 'EE...',
    ]]);

    expect(fn () => makeTransport($client)->postBinary('/print', []))
        ->toThrow(ApiException::class);
});

it('getJson sends GET method', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, []);

    makeTransport($client)->getJson('/x', [], FakeResponse::class);

    expect($client->requests[0]->getMethod())->toBe('GET');
});

it('deleteJson sends DELETE method', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, []);

    makeTransport($client)->deleteJson('/x', [], FakeResponse::class);

    expect($client->requests[0]->getMethod())->toBe('DELETE');
});

it('postCsv returns the raw text/csv body verbatim', function () {
    $client = new FakeHttpClient();
    $client->queueBinary(200, "id,name\n1,foo\n", 'text/csv;charset=UTF-8');

    $body = makeTransport($client)->postCsv('/location/site/csv/100');

    expect($body)->toBe("id,name\n1,foo\n");
});

it('postCsv surfaces JSON {error} envelopes as ApiException', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, ['error' => [
        'code'    => 1,
        'message' => 'Access to detailed/licensed address nomenclatures required',
        'id'      => 'EE...',
    ]]);

    expect(fn () => makeTransport($client)->postCsv('/location/street/csv/100'))
        ->toThrow(ApiException::class);
});

it('postCsv throws InvalidResponseException on empty body', function () {
    $client = new FakeHttpClient();
    $client->queueBinary(200, '', 'text/csv');

    expect(fn () => makeTransport($client)->postCsv('/location/site/csv/100'))
        ->toThrow(InvalidResponseException::class);
});

it('postBinary throws InvalidResponseException on empty body', function () {
    $client = new FakeHttpClient();
    $client->queueBinary(200, '', 'application/pdf');

    expect(fn () => makeTransport($client)->postBinary('/print', []))
        ->toThrow(InvalidResponseException::class);
});

it('throws InvalidResponseException when JSON decodes to a non-object', function () {
    $client = new FakeHttpClient();
    $client->queueRaw(new Response(200, ['Content-Type' => 'application/json'], '"a string"'));

    expect(fn () => makeTransport($client)->postJson('/x', [], FakeResponse::class))
        ->toThrow(InvalidResponseException::class);
});

it('injects clientSystemId from config when not in body', function () {
    $client = new FakeHttpClient();
    $client->queueJson(200, []);

    makeTransport($client, defaultLanguage: null, defaultClientSystemId: 42)
        ->postJson('/x', [], FakeResponse::class);

    expect($client->lastRequestBody()['clientSystemId'])->toBe(42);
});

it('throws InvalidResponseException when the request body cannot be JSON-encoded', function () {
    $client = new FakeHttpClient();

    // Invalid UTF-8 byte sequence makes json_encode fail under JSON_THROW_ON_ERROR.
    expect(fn () => makeTransport($client)->postJson('/x', ['junk' => "\xB1\x31"], FakeResponse::class))
        ->toThrow(InvalidResponseException::class);
});

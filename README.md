# Speedy PHP SDK

> **Warning:** Developer testing version of the library — use at your own risk.

Framework-agnostic PHP SDK for the [Speedy](https://api.speedy.bg/api/docs/) courier API at `https://api.speedy.bg/v1`. Covers all ten Speedy service groups (Shipment, Print, Track, Pickup, Location, Calculate, Client, Validation, Services, Payments) as resource methods that return typed Response DTOs. Works with plain PHP or Laravel.

## Requirements

- PHP 8.2 or higher
- JSON extension
- A PSR-18 HTTP client and PSR-17 request/stream factories (Guzzle provides both)

## Installation

```bash
composer require ux2dev/speedy
```

## Quick start — plain PHP

```php
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Ux2Dev\Speedy\Config\SpeedyConfig;
use Ux2Dev\Speedy\Speedy;
use Ux2Dev\Speedy\Dto\Request\Shipment\CreateShipmentRequest;

$config = new SpeedyConfig(
    userName: 'your-username',
    password: 'your-password',
    language: 'EN',
);

$factory = new HttpFactory();
$speedy  = new Speedy($config, new Client(), $factory, $factory);

$response = $speedy->shipment()->create(new CreateShipmentRequest(
    ref1: 'ORDER-1234',
));

echo $response->id;
```

## Quick start — Laravel

The package auto-registers `SpeedyServiceProvider` and a `Speedy` facade.

Publish the config:

```bash
php artisan vendor:publish --tag=speedy-config
```

Set credentials in `.env`:

```ini
SPEEDY_USERNAME=demo
SPEEDY_PASSWORD=secret
SPEEDY_LANGUAGE=EN
```

Use the facade:

```php
use Ux2Dev\Speedy\Laravel\Facades\Speedy;
use Ux2Dev\Speedy\Dto\Request\Shipment\CreateShipmentRequest;

$response = Speedy::shipment()->create(new CreateShipmentRequest(ref1: 'ORDER-1234'));
```

### Multiple accounts

```php
return [
    'default'  => 'main',
    'accounts' => [
        'main'   => ['user_name' => env('SPEEDY_USERNAME'),   'password' => env('SPEEDY_PASSWORD'),   'language' => 'EN'],
        'second' => ['user_name' => env('SPEEDY_USERNAME_2'), 'password' => env('SPEEDY_PASSWORD_2'), 'language' => 'BG'],
    ],
];
```

```php
Speedy::account('second')->shipment()->create($req);
```

`account()` returns an immutable clone — the default stays untouched.

## How the SDK is organised

| Layer | Location | Purpose |
|-------|----------|---------|
| Config | `Ux2Dev\Speedy\Config\SpeedyConfig` | Base URL + credentials, validated, password redacted |
| Transport | `Ux2Dev\Speedy\Http\SpeedyTransport` | PSR-18 dispatch, auth auto-injection, error mapping |
| Request DTOs | `Ux2Dev\Speedy\Dto\Request\{Group}\*Request` | Generated, `toArray()` |
| Response DTOs | `Ux2Dev\Speedy\Dto\Response\{Group}\*Response` | Generated, `fromArray()` |
| Model DTOs | `Ux2Dev\Speedy\Dto\Model\*` | Shared entities (Address, Office, Site, ...) |
| Resources | `Ux2Dev\Speedy\Resources\{Group}` | Generated, one method per operation |
| Root client | `Ux2Dev\Speedy\Speedy` | Aggregator |
| Laravel | `Ux2Dev\Speedy\Laravel\*` | Service Provider + multi-account Manager + Facade |

Generated from `bin/endpoints.json` (a hand-curated catalog of 45 operations) plus `bin/schemas/` (a snapshot of `https://api.speedy.bg/v1/schema`) by `bin/generate.php`.

## Resources

10 generated resource groups, mirroring Speedy's service organisation:

```
shipment      print        track       pickup
location      calculate    clients     validation
services      payments
```

Every resource method takes a Request DTO plus optional `$language` and `$clientSystemId` overrides, and returns a typed Response DTO:

```php
$speedy->shipment()->create($createShipmentRequest);
$speedy->shipment()->cancel($cancelShipmentRequest);
$speedy->location()->findOffice($findOfficeRequest);
$speedy->track()->track($trackRequest);
$speedy->print()->voucher($printVoucherRequest);
```

## Print Service

Print endpoints currently follow Speedy's documented JSON envelope (`PrintVoucherResponse` carries the PDF as a `pdf` byte-array). When you need the binary path directly, the SDK exposes `Ux2Dev\Speedy\Http\PrintResult` with `body`, `contentType`, `filename`, plus `bytes()`, `saveTo($path)`, `isPdf()`, and `isZpl()` helpers.

## Errors

Successful return paths always carry a "good" Response DTO. When the API responds with a populated `error` field, the transport throws `Ux2Dev\Speedy\Exception\ApiException` with structured fields (`apiCode`, `apiMessage`, `context`, `errorId`, `component`, `httpStatus`, full `body`).

| Exception | When |
|-----------|------|
| `ConfigurationException` | Invalid `SpeedyConfig` input or unknown Laravel account |
| `TransportException` | PSR-18 client failure (network, timeout) |
| `InvalidResponseException` | Empty body, malformed JSON, or unexpected envelope |
| `ApiException` | Speedy returned a non-null `error` field |

All extend `SpeedyException`.

## Regenerating the SDK

```bash
composer speedy:fetch-schemas    # snapshots /v1/schema into bin/schemas
composer speedy:generate         # rewrites src/Resources, src/Dto, and the Speedy/Facade method blocks
vendor/bin/pest                  # snapshot test catches drift
```

## Testing

```bash
composer install
vendor/bin/pest
XDEBUG_MODE=coverage vendor/bin/pest --coverage --min=100
```

The suite mocks a PSR-18 client to exercise every resource method end-to-end.

## License

MIT

# ux2dev/speedy — PHP SDK design

Date: 2026-04-29
Repo: `~/Herd/speedy`
Package: `ux2dev/speedy`
Namespace: `Ux2Dev\Speedy\`

## Goal

A PHP SDK for the Speedy courier API at `https://api.speedy.bg/v1`, modelled on `~/Herd/primio` (resource-based, generator-driven, framework-agnostic core with optional Laravel layer) and on `~/Herd/talobuildings` (per-feature module shape with Actions / Repositories / FormRequests / Models / Jobs / Events) for the opinionated tier.

The package supports three usage tiers from one install:

1. **Thin PHP wrapper** — framework-agnostic SDK only.
2. **Laravel facade** — auto-discovered Service Provider + multi-account Manager + Facade.
3. **Opinionated package** — opt-in tier 3 with migrations, Eloquent models, repositories, actions, jobs, commands, and events for nomenclatures, shipments, and tracking. No webhooks (Speedy does not push notifications — tracking is polling-based).

Coverage scope: all ten Speedy service groups (Shipment, Print, Track, Pickup, Location, Calculate, Client, Validation, Services, Payments).

## Non-goals

- Webhook ingestion. Speedy doesn't push.
- A REST/HTTP layer for consumers' frontends. Tier 3 ships an optional `Http/Requests/` + `Http/Controllers/` for Shipments only, opt-in via separate publish tag.
- Any abstraction over the bare Speedy DTOs (the package surfaces them as PHP DTOs with `toArray()` / `fromArray()`).

## API research summary

- Single base URL: `https://api.speedy.bg/v1`. No sandbox.
- Auth: `userName` + `password` carried in the JSON body of every request. Optional `language` and `clientSystemId` also body-level.
- Mostly POST. Some endpoints support GET. Shipment cancel supports DELETE.
- Print endpoints return raw bytes (PDF) or text (ZPL).
- Errors are embedded in 200 responses as a top-level `error` field with `{context, message, id, code, component}`.
- A downloadable JSON schema bundle at `https://api.speedy.bg/v1/schema` ships ~180 schema files covering every Request, Response, and shared Model entity. Refs use `urn:jsonschema:com:speedy:api:rest:...` URIs.

## Architecture

### Layout

```
ux2dev/speedy
├── bin/
│   ├── schemas/                              # snapshot of /v1/schema, committed
│   ├── endpoints.json                        # hand-curated catalog (~50 ops)
│   └── generate.php                          # regenerates Resources + DTOs
├── src/
│   ├── Speedy.php                            # Tier 1 root client
│   ├── Config/SpeedyConfig.php
│   ├── Http/
│   │   ├── SpeedyTransport.php
│   │   ├── PrintResult.php
│   │   └── ResultList.php
│   ├── Exception/
│   │   ├── SpeedyException.php
│   │   ├── ApiException.php
│   │   ├── ConfigurationException.php
│   │   ├── TransportException.php
│   │   └── InvalidResponseException.php
│   ├── Resources/
│   │   ├── Resource.php                      # hand-written base
│   │   ├── Shipment.php                      # generated
│   │   ├── PrintService.php                  # generated   (class name avoids reserved `Print`)
│   │   ├── Track.php                         # generated
│   │   ├── Pickup.php                        # generated
│   │   ├── Location.php                      # generated
│   │   ├── Calculate.php                     # generated
│   │   ├── Clients.php                       # generated   (accessor `clients()` — see naming notes)
│   │   ├── Validation.php                    # generated
│   │   ├── Services.php                      # generated
│   │   └── Payments.php                      # generated
│   ├── Dto/
│   │   ├── Request/{Group}/*Request.php      # generated
│   │   ├── Response/{Group}/*Response.php    # generated
│   │   └── Model/*.php                       # generated shared entities
│   └── Laravel/                              # Tier 2 — auto-registered
│       ├── SpeedyServiceProvider.php
│       ├── SpeedyManager.php
│       ├── Facades/Speedy.php
│       ├── config/speedy.php
│       └── Modules/                          # Tier 3 — opt-in, talobuildings-shaped
│           ├── Nomenclatures/
│           ├── Shipments/
│           └── Tracking/
└── tests/
```

### Tier 1 — framework-agnostic SDK

#### `Ux2Dev\Speedy\Config\SpeedyConfig`

```php
final readonly class SpeedyConfig
{
    public string  $baseUrl;             // default 'https://api.speedy.bg/v1'
    public string  $userName;            // required
    private string $password;            // required, redacted
    public ?string $language;            // optional default
    public ?int    $clientSystemId;      // optional default
    public int     $timeout;             // default 30s

    public function getPassword(): string;
    public function __debugInfo(): array;        // password ⇒ '[REDACTED]'
    public function __serialize(): array;        // throws LogicException
}
```

Constructor validates: non-empty `userName` and `password`, `timeout >= 1`, `baseUrl` starts with `http(s)://`. Trailing slash on `baseUrl` is normalised. No defaults that hide misconfiguration.

#### `Ux2Dev\Speedy\Http\SpeedyTransport`

```php
final class SpeedyTransport
{
    public function postJson(string $path, array $body, string $responseClass): object;
    public function getJson(string $path, array $body, string $responseClass): object;
    public function deleteJson(string $path, array $body, string $responseClass): object;
    public function postBinary(string $path, array $body): PrintResult;
}
```

Each method:

1. Auto-injects `userName`, `password`, `language`, `clientSystemId` from config into `$body`. Per-call values pre-merged by the resource take precedence.
2. Sends via the injected PSR-18 client with PSR-17 factories.
3. JSON variants: decode response, check for top-level `error` field. If `error` is non-null → throw `ApiException` with `code`, `message`, `context`, `errorId`, `component`, `httpStatus`, full decoded `body`.
4. Binary variant: returns `PrintResult { body, contentType, filename }` populated from response headers.
5. PSR-18 client failure → `TransportException`. Empty body / malformed JSON / unexpected shape → `InvalidResponseException`.

Successful return path always carries a "good" Response DTO — consumers never see a half-failed response.

#### `Ux2Dev\Speedy\Http\PrintResult`

```php
final class PrintResult
{
    public function __construct(
        public readonly string  $body,
        public readonly string  $contentType,
        public readonly ?string $filename,
    ) {}

    public function bytes(): string;
    public function saveTo(string $path): int;
    public function isPdf(): bool;
    public function isZpl(): bool;
}
```

#### Generated Resources

Example shape (all resource methods follow one of three templates):

```php
final class Shipment extends Resource
{
    public function create(CreateShipmentRequest $req, ?string $language = null): CreateShipmentResponse
    {
        return $this->transport->postJson(
            '/shipment',
            $req->toArray($language),
            CreateShipmentResponse::class,
        );
    }

    public function cancel(CancelShipmentRequest $req, ?string $language = null): CancelShipmentResponse;
    public function info(ShipmentInformationRequest $req, ?string $language = null): ShipmentInformationResponse;
    public function search(FindParcelsByRefRequest $req, ?string $language = null): FindParcelsByRefResponse;
}
```

Every resource method takes one Request DTO + optional `$language` / `$clientSystemId` overrides, returns the typed Response DTO (or `PrintResult` for binary).

#### Generated DTOs

- **Request DTOs** (`Dto/Request/{Group}/{Operation}Request.php`): `final readonly class`, ctor with all schema fields. Nested object properties typed as Model DTOs. Arrays-of-objects typed `?array` with `@var` doc. `toArray()` excludes nulls. Auto-injected `userName` / `password` / `language` / `clientSystemId` are stripped from generated DTOs — Transport adds them.
- **Response DTOs** (`Dto/Response/{Group}/{Operation}Response.php`): same shape with `static fromArray()`. Scalars typed `mixed` if upstream schemas are inconsistent for that field; nested Model DTOs typed.
- **Model DTOs** (`Dto/Model/*.php`): one per shared entity referenced by at least one Request/Response (Address, AddressLocation, Country, State, Site, Street, Postcode, Office, OfficeWorkingTimeSchedule, Parcel, ContractInfo, Error, etc.). Have both `toArray()` and `fromArray()` so they round-trip.

#### Exceptions

```
SpeedyException                (base, abstract)
├── ConfigurationException
├── TransportException
├── InvalidResponseException
└── ApiException
    public ?int    $code;
    public ?string $message;
    public ?string $context;
    public ?string $errorId;
    public ?string $component;
    public ?int    $httpStatus;
    public array   $body;
```

### Generator

#### Inputs

- **`bin/schemas/`** — flat directory with all 180 `*.schema.json` files unzipped from `https://api.speedy.bg/v1/schema`. Snapshot, refreshed on demand. Never hand-edited.
- **`bin/endpoints.json`** — hand-curated catalog. One entry per public operation:

```json
[
  {
    "group":    "Shipment",
    "name":     "create",
    "method":   "POST",
    "path":     "/shipment",
    "request":  "CreateShipmentRequest",
    "response": "CreateShipmentResponse",
    "returns":  "json"
  },
  {
    "group":    "Print",
    "name":     "voucher",
    "method":   "POST",
    "path":     "/print",
    "request":  "PrintVoucherRequest",
    "response": null,
    "returns":  "bytes"
  }
]
```

`returns: "bytes"` ⇒ resource method returns `PrintResult`. `response: null` is permitted only when `returns: "bytes"`.

#### Behaviour

`php bin/generate.php`:

1. Wipes `src/Resources/*.php` (preserving `Resource.php`), `src/Dto/Request/`, `src/Dto/Response/`, `src/Dto/Model/`.
2. Resolves every `urn:jsonschema:` ref in the schema bundle. Any schema referenced from a Request or Response in `endpoints.json` becomes a Model DTO under `src/Dto/Model/`.
3. For each `endpoints.json` entry: emits the Request DTO, the Response DTO (when not bytes), and the corresponding method on the group's Resource class.
4. Groups operations by `group`, emits `src/Resources/{Group}.php` with one method per op.
5. Emits the resource accessor block + `@method` annotations into `src/Speedy.php` and `src/Laravel/Facades/Speedy.php`. The shells of those files are hand-written — only the regenerated blocks are replaced.

#### Hand-written, never touched

- `src/Speedy.php` ctor and transport wiring (only resource accessors + `@method` are regenerated).
- `src/Config/SpeedyConfig.php`
- `src/Http/SpeedyTransport.php`, `PrintResult.php`, `ResultList.php`
- `src/Resources/Resource.php` (base)
- All exception classes
- `src/Laravel/SpeedyManager.php`, `SpeedyServiceProvider.php`
- `src/Laravel/Facades/Speedy.php` (only the `@method` doc-block is regenerated)
- All of `src/Laravel/Modules/`

#### Regeneration workflow

```
composer speedy:fetch-schemas
# edit bin/endpoints.json if Speedy added new operations
composer speedy:generate
vendor/bin/pest                          # snapshot test catches unexpected drift
```

`tests/Bin/GeneratorSnapshotTest.php` re-runs the generator against a temp dir and diffs output against the committed `src/`, failing CI on drift.

### Tier 2 — Laravel facade

`SpeedyServiceProvider` (auto-registered via composer extra):

- Merges `config/speedy.php`.
- Registers `SpeedyManager` as a singleton (`alias 'speedy'`).
- Publishes `config/speedy.php` under tag `speedy-config`.

`SpeedyManager`:

- Multi-account, primio-style. `account('foo')` returns an immutable clone with the target account active.
- Lazy: `Speedy` instances cached by account name. The accessor that returns the active `Speedy` is named **`instance()`** (not `client()`, to avoid colliding with the `clients()` resource on `Speedy`).
- `__call` forwards undefined methods to the active `Speedy` (so `Speedy::shipment()` works as `Speedy::instance()->shipment()`, and `Speedy::clients()` reaches `Resources\Clients`).
- Builds a `Speedy` from `accounts.<name>.{base_url, user_name, password, language, client_system_id, timeout}`.
- Throws `ConfigurationException` on unknown account.

### Naming notes

- `Resources\PrintService` (not `Print`): `Print` is a PHP reserved keyword and cannot be used as a class name. The accessor method is `$speedy->print()` (allowed since PHP 7+).
- `Resources\Clients` with accessor `clients()` (plural): Speedy's "Client Service" returns client/contact records. The plural avoids ambiguity with the word "client" everywhere else (PSR-18 client, manager instance, etc.). The Manager's `instance()` name (above) avoids colliding with this accessor at the Facade.
- `Resources\Services` with accessor `services()`: maps to Speedy's "Services Service" (lists available courier services). Generic but unambiguous in context.

`Facade\Speedy`: standard Laravel facade pointing at `SpeedyManager`. `@method` annotations regenerated for every resource.

`config/speedy.php`:

```php
return [
    'default'  => env('SPEEDY_DEFAULT_ACCOUNT', 'main'),
    'accounts' => [
        'main' => [
            'base_url'         => env('SPEEDY_BASE_URL', 'https://api.speedy.bg/v1'),
            'user_name'        => env('SPEEDY_USERNAME'),
            'password'         => env('SPEEDY_PASSWORD'),
            'language'         => env('SPEEDY_LANGUAGE', 'EN'),
            'client_system_id' => env('SPEEDY_CLIENT_SYSTEM_ID'),
            'timeout'          => (int) env('SPEEDY_TIMEOUT', 30),
        ],
    ],

    'nomenclatures' => [
        'enabled'  => env('SPEEDY_SYNC_NOMENCLATURES', false),
        'entities' => ['countries', 'states', 'sites', 'streets', 'postcodes', 'offices'],
        'schedule' => '0 3 * * *',
    ],

    'shipments' => [
        'enabled'      => env('SPEEDY_PERSIST_SHIPMENTS', false),
        'auto_persist' => env('SPEEDY_AUTO_PERSIST', false),
    ],

    'tracking' => [
        'enabled'    => env('SPEEDY_TRACK_SHIPMENTS', false),
        'poll_batch' => 200,
        'schedule'   => '*/15 * * * *',
    ],
];
```

### Tier 3 — opinionated sub-modules

Each sub-module is independently installable and shaped like a `~/Herd/talobuildings` module: `Models/`, `Repositories/{Contracts/, Eloquent…}`, `Actions/`, `Jobs/`, `Console/`, `Providers/`, `database/migrations/` (and optionally `Http/Requests/` + `Http/Controllers/` + `Events/`).

Tier 3 ServiceProviders are **not** auto-registered. The consumer adds them to `config/app.php` (or to a host module's providers list) explicitly. The provider class FQNs are:

- `Ux2Dev\Speedy\Laravel\Modules\Nomenclatures\Providers\NomenclaturesServiceProvider`
- `Ux2Dev\Speedy\Laravel\Modules\Shipments\Providers\ShipmentsServiceProvider`
- `Ux2Dev\Speedy\Laravel\Modules\Tracking\Providers\TrackingServiceProvider`

Each sub-module exposes its own publish tags so consumers take only what they need.

#### 3a. Nomenclatures

Tables (one migration per, prefix `speedy_`):

| Table | Purpose | Key columns |
|---|---|---|
| `speedy_countries` | Country reference | `id`, `name_en`, `name_local`, `iso_alpha2`, `iso_alpha3`, `dial_code`, `synced_at` |
| `speedy_states` | States/regions per country | `id`, `country_id`, `name`, `synced_at` |
| `speedy_sites` | Cities/villages | `id`, `country_id`, `state_id`, `type`, `name_en`, `name_local`, `post_code`, `municipality`, `region`, `synced_at` |
| `speedy_streets` | Streets per site | `id`, `site_id`, `name_en`, `name_local`, `prefix`, `synced_at` |
| `speedy_postcodes` | Postcodes per site | `id`, `site_id`, `post_code`, `district`, `synced_at` |
| `speedy_offices` | Speedy offices and APMs | `id`, `country_id`, `site_id`, `name_en`, `name_local`, `address`, `latitude`, `longitude`, `working_time_json`, `office_type`, `is_apm`, `synced_at` |
| `speedy_points_of_interest` | POIs | `id`, `site_id`, `type`, `name_en`, `name_local`, `synced_at` |
| `speedy_complexes` | Apartment complexes | `id`, `site_id`, `name_en`, `name_local`, `synced_at` |
| `speedy_blocks` | Block numbers within complexes | `id`, `site_id`, `complex_id`, `block_no`, `synced_at` |

`id` is the Speedy-assigned identifier (not auto-increment). `synced_at` lets us prune stale rows.

Components:

- **Repositories**: one Eloquent + Interface pair per model, bound in `NomenclaturesServiceProvider`.
- **Action** `SyncNomenclaturesAction`: pulls each entity type via Tier 1 SDK, upserts in chunks, marks `synced_at`. Granular per-entity enable/disable from config.
- **Job** `SyncNomenclaturesJob`: queueable, retry-aware, wraps the action.
- **Console** `php artisan speedy:sync-nomenclatures [--account=foo] [--only=offices,sites]`. Schedulable from host.

Publish tags: `speedy-nomenclatures-migrations`, `speedy-nomenclatures-config`, `speedy-nomenclatures-providers`.

#### 3b. Shipments

Tables:

| Table | Purpose | Key columns |
|---|---|---|
| `speedy_shipments` | One row per created shipment | `id` (auto), `account`, `speedy_id`, `ref1`, `ref2`, `consolidation_ref`, `service_id`, `sender_json`, `recipient_json`, `payment_json`, `content_json`, `status`, `created_at`, `cancelled_at`, `last_synced_at` |
| `speedy_shipment_parcels` | One row per parcel within a shipment | `id` (auto), `shipment_id` (FK), `speedy_parcel_id`, `seq`, `size`, `weight`, `barcode_data` |

`status` is an enum: `pending`, `created`, `cancelled`, `picked_up`, `in_transit`, `out_for_delivery`, `delivered`, `returned`, `failed`. Updated by the Tracking sub-module on transitions.

`*_json` columns store the snapshot of the request/response so audits and cancels do not require re-fetching from Speedy.

Components:

- **Repository** `ShipmentRepositoryInterface` ↔ `EloquentShipmentRepository`.
- **Actions** `CreateAndStoreShipmentAction`, `CancelStoredShipmentAction`. Activated via DI.
- **Optional Http layer** (separate publish tag): `Http/Requests/{CreateShipmentRequest, CancelShipmentRequest}` (FormRequests with validation), `Http/Controllers/ShipmentsController`. Most consumers won't need this.
- **Events** `ShipmentCreated($shipment)`, `ShipmentCancelled($shipment)` fired from actions (not from SDK transport).
- **Auto-persist toggle** `config('speedy.shipments.auto_persist', false)`. When `true`, a Manager-level decorator wraps the SDK so plain `Speedy::shipment()->create()` also persists. Default `false` — persistence is opt-in via the action.

Publish tags: `speedy-shipments-migrations`, `speedy-shipments-providers`, `speedy-shipments-http`.

#### 3c. Tracking

Tables:

| Table | Purpose | Key columns |
|---|---|---|
| `speedy_shipment_events` | Operation timeline per parcel | `id` (auto), `shipment_id` (FK), `parcel_id` (FK, nullable), `speedy_operation_id`, `operation_code`, `operation_label`, `office_id`, `occurred_at`, `recorded_at`, `payload_json` |

Unique index on `(parcel_id, speedy_operation_id)` so re-polling is idempotent.

Components:

- **Repository** `ShipmentEventRepositoryInterface` ↔ `EloquentShipmentEventRepository`.
- **Action** `PollOpenShipmentsAction`: finds shipments in non-terminal status, calls Track service in batches (Speedy's bulk-track endpoint accepts arrays), upserts new events, derives the latest status, fires events on transitions.
- **Job** `PollShipmentStatusJob`: queueable, idempotent, single-instance lock.
- **Console** `php artisan speedy:track-open-shipments [--account=foo] [--limit=500]`. Schedulable.
- **Events** (fired by the action on transition):
  - `ShipmentStatusChanged($shipment, $oldStatus, $newStatus, $event)`
  - `ShipmentDelivered($shipment)`
  - `ShipmentReturned($shipment)`
  - `ShipmentFailed($shipment, $reason)`

Publish tags: `speedy-tracking-migrations`, `speedy-tracking-providers`.

### Cross-cutting concerns

- **Account scoping**: every tier 3 table has an `account` column matching the `config('speedy.accounts.*')` key. One app can track shipments across multiple Speedy contracts without collision.
- **Migrations are not auto-run**. The package only publishes them; `php artisan migrate` is the consumer's responsibility.
- **Tier 3 sub-modules behind `enabled` flags**: each `enabled => false` short-circuits the corresponding ServiceProvider's bindings, so registering the provider on a not-yet-configured environment is cheap and safe.

## Data flow

A single create-shipment call from a Laravel app using Tier 2 only:

```
host code
  └─ Speedy::shipment()->create($createShipmentRequest)
       └─ SpeedyManager::__call('shipment', [])
            └─ SpeedyManager::instance() → Speedy
                 └─ Speedy::shipment() → Resources\Shipment
                      └─ Resources\Shipment::create($req)
                           └─ SpeedyTransport::postJson('/shipment', $req->toArray() + auto-injected creds, CreateShipmentResponse::class)
                                ├─ PSR-18 client → 'POST https://api.speedy.bg/v1/shipment'
                                ├─ decode JSON
                                ├─ if (response.error !== null) throw ApiException
                                └─ return CreateShipmentResponse::fromArray($decoded)
```

Same call with Tier 3 Shipments + auto-persist enabled: a Manager-level decorator wraps the resource so the result is also passed through `CreateAndStoreShipmentAction`, which writes to `speedy_shipments`/`speedy_shipment_parcels` and fires `ShipmentCreated`.

## Error handling

- `ApiException` is thrown from Tier 1 transport whenever the response body contains a non-null top-level `error`. Carries the full decoded body plus structured fields for typical handling.
- `TransportException` for PSR-18-level errors (timeouts, network).
- `InvalidResponseException` for malformed JSON or unexpected envelope.
- `ConfigurationException` for invalid `SpeedyConfig` input or unknown account in the Manager.
- All extend `SpeedyException`. Consumers can catch the base for blanket handling or specific subtypes for targeted recovery.

Tier 3 actions never swallow these — they let them bubble unless explicitly retrying (jobs use Laravel's retry mechanics).

## Testing

Pest. Testbench for Laravel pieces. Goal: 100% line coverage on `src/`, same as primio.

- **Tier 1**:
  - `tests/Http/FakeHttpClient.php` — programmable PSR-18 client, records every request.
  - `tests/Http/SpeedyTransportTest.php` — auto-injection, error-to-exception mapping, binary path, all four methods.
  - `tests/Config/SpeedyConfigTest.php` — validation, redaction, serialize-block.
  - `tests/Resources/ResourcesIntegrationTest.php` — for every entry in `bin/endpoints.json`, exercise the resource method against the fake client and assert outbound URL, method, body shape (auto-injected fields present, language override honored), and that the typed Response DTO returns with the expected structure. Single test gives full coverage of all generated code.
  - `tests/Bin/GeneratorSnapshotTest.php` — generator output stable.
  - `tests/Dto/RoundtripTest.php` — Request `toArray()` + Response `fromArray()` round-trips.

- **Tier 2**:
  - `tests/Laravel/SpeedyManagerTest.php` — multi-account resolution, immutable `account()` clone, unknown account throws, lazy caching.
  - `tests/Laravel/PublishConfigTest.php` — `vendor:publish --tag=speedy-config` writes the file.
  - `tests/Laravel/FacadeTest.php` — facade resolves to manager and forwards calls.

- **Tier 3** (one suite per sub-module, each isolated with `RefreshDatabase`):
  - Nomenclatures: `SyncNomenclaturesActionTest` (canned Location responses → upserts → idempotency), per-repository tests.
  - Shipments: `CreateAndStoreShipmentActionTest` (persists + fires `ShipmentCreated`), `AutoPersistDecoratorTest` (auto-persist flag honored).
  - Tracking: `PollOpenShipmentsActionTest` (mixed transitions, idempotent re-poll, transition events fired).

Tier 3 sub-module migrations and stubs are excluded from coverage — they're publish artifacts, not runtime code.

## Package metadata

```json
{
    "name": "ux2dev/speedy",
    "description": "Framework-agnostic PHP SDK for Speedy courier (api.speedy.bg)",
    "type": "library",
    "license": "MIT",
    "require": {
        "php": "^8.2",
        "ext-json": "*",
        "psr/http-client": "^1.0",
        "psr/http-factory": "^1.0"
    },
    "require-dev": {
        "pestphp/pest": "^4.0",
        "guzzlehttp/guzzle": "^7.0",
        "orchestra/testbench": "^10.0"
    },
    "suggest": {
        "guzzlehttp/guzzle": "Supplies PSR-18 client + PSR-17 factories out of the box",
        "illuminate/support": "Required for Tier 2 (Laravel facade) and Tier 3 (opinionated modules)"
    },
    "autoload": {
        "psr-4": { "Ux2Dev\\Speedy\\": "src/" }
    },
    "autoload-dev": {
        "psr-4": { "Ux2Dev\\Speedy\\Tests\\": "tests/" }
    },
    "extra": {
        "laravel": {
            "providers": [
                "Ux2Dev\\Speedy\\Laravel\\SpeedyServiceProvider"
            ],
            "aliases": {
                "Speedy": "Ux2Dev\\Speedy\\Laravel\\Facades\\Speedy"
            }
        }
    },
    "scripts": {
        "speedy:fetch-schemas": "rm -rf bin/schemas && mkdir -p bin/schemas && curl -sSL https://api.speedy.bg/v1/schema -o /tmp/speedy-schema.zip && unzip -q /tmp/speedy-schema.zip -d bin/schemas",
        "speedy:generate":      "php bin/generate.php"
    }
}
```

Auto-discovery registers Tier 2 only. Tier 3 sub-module providers must be added explicitly by the consumer.

## Build sequence

1. Bootstrap package skeleton (`composer.json`, `src/`, `tests/`, `phpunit.xml`, `README.md`, `LICENSE`).
2. Hand-write Tier 1 core: `SpeedyConfig`, `SpeedyTransport`, exceptions, `Resource` base, `ResultList`, `PrintResult`.
3. Build the generator: `composer speedy:fetch-schemas`, snapshot `bin/schemas/`, hand-write initial `bin/endpoints.json` with ~10 ops, `bin/generate.php`.
4. Verify generator output by running it and writing the integration test against the seed ops.
5. Expand `bin/endpoints.json` to the full ~50 operations across all 10 service groups; regenerate; integration test stays green.
6. Hand-write Tier 2: Manager, ServiceProvider, Facade, `config/speedy.php`.
7. Hand-write Tier 3 sub-modules in this order: Nomenclatures → Shipments → Tracking. Each lands with its migrations, models, repositories, action, job, command, provider, and tests.
8. README + final coverage push to 100%.

## Open questions / risks

- **Endpoint catalog accuracy**: Speedy's docs list operations as descriptive sections rather than a machine-readable manifest. The hand-curated `bin/endpoints.json` will need careful first-pass authoring against the docs and ad-hoc verification against the live API. Snapshot test catches drift in our generated code, but not drift in our catalog vs. reality.
- **Schema gaps**: a few schemas (notably error / inline operations) may be incomplete. Generator falls back to a generic Response DTO that just carries `array $data` (same pattern as primio's `renderGenericResultDto`).
- **Print response headers**: empirically verify `Content-Disposition` is present on PDF responses; otherwise `PrintResult::$filename` will be `null` for those calls.
- **Bulk-track shape**: the Track service's bulk endpoint shape needs verifying when wiring the Tracking sub-module — the action needs to know whether it can submit one batch or has to chunk.
- **ZPL content type**: confirm Speedy emits `text/plain` or a more specific media type for ZPL so `PrintResult::isZpl()` can be exact.

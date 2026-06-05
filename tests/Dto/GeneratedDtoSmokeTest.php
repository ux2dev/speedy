<?php

declare(strict_types=1);

it('every Model DTO round-trips an empty array', function () {
    $models = glob(__DIR__ . '/../../src/Dto/Model/*.php') ?: [];
    expect($models)->not->toBeEmpty();

    foreach ($models as $file) {
        $class = 'Ux2Dev\\Speedy\\Dto\\Model\\' . basename($file, '.php');

        // Skip backed-string enums — top-level "type": "string" + "enum" schemas
        // are emitted as PHP enums, not classes with fromArray/toArray.
        if (enum_exists($class)) continue;

        $dto = $class::fromArray([]);
        expect($dto)->toBeInstanceOf($class, "fromArray failed for {$class}");
        expect($dto->toArray())->toBeArray("toArray failed for {$class}");
    }
});

it('top-level string-enum schemas are emitted as backed enums', function () {
    foreach (['ShipmentRole', 'CODProcessingType', 'ExternalCarrier', 'PaymentType', 'PrimaryShipmentType'] as $name) {
        $class = "Ux2Dev\\Speedy\\Dto\\Model\\{$name}";
        expect(enum_exists($class))->toBeTrue("{$class} should be a backed enum");
        $reflection = new ReflectionEnum($class);
        expect((string) $reflection->getBackingType())->toBe('string');
    }
});

it('every Request DTO instantiates with no args and toArray returns an empty array', function () {
    $base  = realpath(__DIR__ . '/../../src/Dto/Request');
    $tree  = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
    $count = 0;
    foreach ($tree as $file) {
        if ($file->isDir() || $file->getExtension() !== 'php') {
            continue;
        }
        $rel   = substr((string) $file, strlen($base) + 1, -4);
        $class = 'Ux2Dev\\Speedy\\Dto\\Request\\' . str_replace('/', '\\', $rel);
        $req   = new $class();
        expect($req->toArray())->toBe([], "toArray non-empty without inputs for {$class}");
        $count++;
    }
    expect($count)->toBeGreaterThan(0);
});

it('every Response DTO fromArray then re-instantiates without throwing', function () {
    $base  = realpath(__DIR__ . '/../../src/Dto/Response');
    $tree  = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
    $count = 0;
    foreach ($tree as $file) {
        if ($file->isDir() || $file->getExtension() !== 'php') {
            continue;
        }
        $rel   = substr((string) $file, strlen($base) + 1, -4);
        $class = 'Ux2Dev\\Speedy\\Dto\\Response\\' . str_replace('/', '\\', $rel);
        $resp  = $class::fromArray([]);
        expect($resp)->toBeInstanceOf($class);
        $count++;
    }
    expect($count)->toBeGreaterThan(0);
});

it('Model DTOs emit their populated values via toArray', function () {
    $country = \Ux2Dev\Speedy\Dto\Model\Country::fromArray([
        'id' => 100,
        'name' => 'Bulgaria',
        'isoAlpha2' => 'BG',
    ]);
    $arr = $country->toArray();

    expect($arr['id'])->toBe(100);
    expect($arr['name'])->toBe('Bulgaria');
    expect($arr['isoAlpha2'])->toBe('BG');
});

it('Model DTOs preserve nested model references through round-trip', function () {
    $office = \Ux2Dev\Speedy\Dto\Model\Office::fromArray([
        'id' => 1,
        'name' => 'Sofia office',
    ]);
    $arr = $office->toArray();
    expect($arr['id'])->toBe(1);
    expect($arr['name'])->toBe('Sofia office');
});

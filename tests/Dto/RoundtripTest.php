<?php

declare(strict_types=1);

use Ux2Dev\Speedy\Dto\Request\Shipment\CreateShipmentRequest;
use Ux2Dev\Speedy\Dto\Response\Shipment\CreateShipmentResponse;

it('Request DTO toArray drops null fields', function () {
    $req = new CreateShipmentRequest(ref1: 'ABC');

    $arr = $req->toArray();

    expect($arr)->toHaveKey('ref1');
    expect($arr['ref1'])->toBe('ABC');
    expect($arr)->not->toHaveKey('ref2');
});

it('Response DTO fromArray reads null fields safely', function () {
    $resp = CreateShipmentResponse::fromArray([]);

    expect($resp)->toBeInstanceOf(CreateShipmentResponse::class);
});

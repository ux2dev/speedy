<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Resources;

use Ux2Dev\Speedy\Dto\Request\Shipment\CancelShipmentRequest;
use Ux2Dev\Speedy\Dto\Request\Shipment\CreateShipmentRequest;
use Ux2Dev\Speedy\Dto\Request\Shipment\ShipmentInformationRequest;
use Ux2Dev\Speedy\Dto\Response\Shipment\CancelShipmentResponse;
use Ux2Dev\Speedy\Dto\Response\Shipment\CreateShipmentResponse;
use Ux2Dev\Speedy\Dto\Response\Shipment\ShipmentInformationResponse;
use Ux2Dev\Speedy\Resources\Resource;

final class Shipment extends Resource
{
    public function create(CreateShipmentRequest $request, ?string $language = null, ?int $clientSystemId = null): CreateShipmentResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/shipment', $body, CreateShipmentResponse::class);
    }

    public function cancel(CancelShipmentRequest $request, ?string $language = null, ?int $clientSystemId = null): CancelShipmentResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/shipment/cancel', $body, CancelShipmentResponse::class);
    }

    public function info(ShipmentInformationRequest $request, ?string $language = null, ?int $clientSystemId = null): ShipmentInformationResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/shipment/info', $body, ShipmentInformationResponse::class);
    }
}
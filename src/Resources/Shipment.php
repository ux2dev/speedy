<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Resources;

use Ux2Dev\Speedy\Dto\Request\Shipment\AddParcelRequest;
use Ux2Dev\Speedy\Dto\Request\Shipment\BarcodeInformationRequest;
use Ux2Dev\Speedy\Dto\Request\Shipment\CancelShipmentRequest;
use Ux2Dev\Speedy\Dto\Request\Shipment\CreateShipmentRequest;
use Ux2Dev\Speedy\Dto\Request\Shipment\FinalizePendingShipmentRequest;
use Ux2Dev\Speedy\Dto\Request\Shipment\FindParcelsByRefRequest;
use Ux2Dev\Speedy\Dto\Request\Shipment\HandOverToCourierRequest;
use Ux2Dev\Speedy\Dto\Request\Shipment\HandOverToMidwayCarrierRequest;
use Ux2Dev\Speedy\Dto\Request\Shipment\SecondaryShipmentsRequest;
use Ux2Dev\Speedy\Dto\Request\Shipment\ShipmentInformationRequest;
use Ux2Dev\Speedy\Dto\Request\Shipment\UpdateShipmentPropertiesRequest;
use Ux2Dev\Speedy\Dto\Request\Shipment\UpdateShipmentRequest;
use Ux2Dev\Speedy\Dto\Response\Shipment\AddParcelResponse;
use Ux2Dev\Speedy\Dto\Response\Shipment\BarcodeInformationResponse;
use Ux2Dev\Speedy\Dto\Response\Shipment\CancelShipmentResponse;
use Ux2Dev\Speedy\Dto\Response\Shipment\CreateShipmentResponse;
use Ux2Dev\Speedy\Dto\Response\Shipment\FinalizePendingShipmentResponse;
use Ux2Dev\Speedy\Dto\Response\Shipment\FindParcelsByRefResponse;
use Ux2Dev\Speedy\Dto\Response\Shipment\HandOverToCourierResponse;
use Ux2Dev\Speedy\Dto\Response\Shipment\HandOverToMidwayCarrierResponse;
use Ux2Dev\Speedy\Dto\Response\Shipment\SecondaryShipmentsResponse;
use Ux2Dev\Speedy\Dto\Response\Shipment\ShipmentInformationResponse;
use Ux2Dev\Speedy\Dto\Response\Shipment\UpdateShipmentResponse;
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

    public function addParcel(AddParcelRequest $request, ?string $language = null, ?int $clientSystemId = null): AddParcelResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/shipment/add_parcel', $body, AddParcelResponse::class);
    }

    public function finalize(FinalizePendingShipmentRequest $request, ?string $language = null, ?int $clientSystemId = null): FinalizePendingShipmentResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/shipment/finalize', $body, FinalizePendingShipmentResponse::class);
    }

    public function info(ShipmentInformationRequest $request, ?string $language = null, ?int $clientSystemId = null): ShipmentInformationResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/shipment/info', $body, ShipmentInformationResponse::class);
    }

    public function secondary(SecondaryShipmentsRequest $request, ?string $language = null, ?int $clientSystemId = null): SecondaryShipmentsResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/shipment/secondary', $body, SecondaryShipmentsResponse::class);
    }

    public function update(UpdateShipmentRequest $request, ?string $language = null, ?int $clientSystemId = null): UpdateShipmentResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/shipment/update', $body, UpdateShipmentResponse::class);
    }

    public function updateProperties(UpdateShipmentPropertiesRequest $request, ?string $language = null, ?int $clientSystemId = null): UpdateShipmentResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/shipment/update/properties', $body, UpdateShipmentResponse::class);
    }

    public function search(FindParcelsByRefRequest $request, ?string $language = null, ?int $clientSystemId = null): FindParcelsByRefResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/shipment/search', $body, FindParcelsByRefResponse::class);
    }

    public function handover(HandOverToCourierRequest $request, ?string $language = null, ?int $clientSystemId = null): HandOverToCourierResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/shipment/handover', $body, HandOverToCourierResponse::class);
    }

    public function handoverToMidwayCarrier(HandOverToMidwayCarrierRequest $request, ?string $language = null, ?int $clientSystemId = null): HandOverToMidwayCarrierResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/shipment/handover-to-midway-carrier', $body, HandOverToMidwayCarrierResponse::class);
    }

    public function barcodeInformation(BarcodeInformationRequest $request, ?string $language = null, ?int $clientSystemId = null): BarcodeInformationResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/shipment/barcode-information', $body, BarcodeInformationResponse::class);
    }
}
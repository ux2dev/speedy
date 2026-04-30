<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Resources;

use Ux2Dev\Speedy\Dto\Request\Location\FindCountryRequest;
use Ux2Dev\Speedy\Dto\Request\Location\FindOfficeRequest;
use Ux2Dev\Speedy\Dto\Response\Location\FindCountryResponse;
use Ux2Dev\Speedy\Dto\Response\Location\FindOfficeResponse;
use Ux2Dev\Speedy\Resources\Resource;

final class Location extends Resource
{
    public function findCountry(FindCountryRequest $request, ?string $language = null, ?int $clientSystemId = null): FindCountryResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/location/country', $body, FindCountryResponse::class);
    }

    public function findOffice(FindOfficeRequest $request, ?string $language = null, ?int $clientSystemId = null): FindOfficeResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/location/office', $body, FindOfficeResponse::class);
    }
}
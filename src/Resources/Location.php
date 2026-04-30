<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Resources;

use Ux2Dev\Speedy\Dto\Request\Location\FindCountryRequest;
use Ux2Dev\Speedy\Dto\Request\Location\FindNearestOfficesRequest;
use Ux2Dev\Speedy\Dto\Request\Location\FindOfficeRequest;
use Ux2Dev\Speedy\Dto\Request\Location\FindSiteRequest;
use Ux2Dev\Speedy\Dto\Request\Location\FindStreetRequest;
use Ux2Dev\Speedy\Dto\Request\Location\GetAllCountriesRequest;
use Ux2Dev\Speedy\Dto\Request\Location\GetAllSitesRequest;
use Ux2Dev\Speedy\Dto\Request\Location\GetAllStreetsRequest;
use Ux2Dev\Speedy\Dto\Request\Location\GetCountryRequest;
use Ux2Dev\Speedy\Dto\Request\Location\GetOfficeRequest;
use Ux2Dev\Speedy\Dto\Request\Location\GetSiteRequest;
use Ux2Dev\Speedy\Dto\Request\Location\GetStreetRequest;
use Ux2Dev\Speedy\Dto\Request\Location\SearchAddressRequest;
use Ux2Dev\Speedy\Dto\Response\Location\FindCountryResponse;
use Ux2Dev\Speedy\Dto\Response\Location\FindNearestOfficesResponse;
use Ux2Dev\Speedy\Dto\Response\Location\FindOfficeResponse;
use Ux2Dev\Speedy\Dto\Response\Location\FindSiteResponse;
use Ux2Dev\Speedy\Dto\Response\Location\FindStreetResponse;
use Ux2Dev\Speedy\Dto\Response\Location\GetCountryResponse;
use Ux2Dev\Speedy\Dto\Response\Location\GetOfficeResponse;
use Ux2Dev\Speedy\Dto\Response\Location\GetSiteResponse;
use Ux2Dev\Speedy\Dto\Response\Location\GetStreetResponse;
use Ux2Dev\Speedy\Dto\Response\Location\SearchAddressResponse;
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

    public function getCountry(GetCountryRequest $request, ?string $language = null, ?int $clientSystemId = null): GetCountryResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/location/country/getById', $body, GetCountryResponse::class);
    }

    public function getAllCountries(GetAllCountriesRequest $request, ?string $language = null, ?int $clientSystemId = null): FindCountryResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/location/country/getAll', $body, FindCountryResponse::class);
    }

    public function findSite(FindSiteRequest $request, ?string $language = null, ?int $clientSystemId = null): FindSiteResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/location/site', $body, FindSiteResponse::class);
    }

    public function getSite(GetSiteRequest $request, ?string $language = null, ?int $clientSystemId = null): GetSiteResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/location/site/getById', $body, GetSiteResponse::class);
    }

    public function getAllSites(GetAllSitesRequest $request, ?string $language = null, ?int $clientSystemId = null): FindSiteResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/location/site/getAll', $body, FindSiteResponse::class);
    }

    public function findStreet(FindStreetRequest $request, ?string $language = null, ?int $clientSystemId = null): FindStreetResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/location/street', $body, FindStreetResponse::class);
    }

    public function getStreet(GetStreetRequest $request, ?string $language = null, ?int $clientSystemId = null): GetStreetResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/location/street/getById', $body, GetStreetResponse::class);
    }

    public function getAllStreets(GetAllStreetsRequest $request, ?string $language = null, ?int $clientSystemId = null): FindStreetResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/location/street/getAll', $body, FindStreetResponse::class);
    }

    public function findOffice(FindOfficeRequest $request, ?string $language = null, ?int $clientSystemId = null): FindOfficeResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/location/office', $body, FindOfficeResponse::class);
    }

    public function findNearestOffices(FindNearestOfficesRequest $request, ?string $language = null, ?int $clientSystemId = null): FindNearestOfficesResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/location/office/nearest', $body, FindNearestOfficesResponse::class);
    }

    public function getOffice(GetOfficeRequest $request, ?string $language = null, ?int $clientSystemId = null): GetOfficeResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/location/office/getById', $body, GetOfficeResponse::class);
    }

    public function searchAddress(SearchAddressRequest $request, ?string $language = null, ?int $clientSystemId = null): SearchAddressResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/location/address', $body, SearchAddressResponse::class);
    }
}
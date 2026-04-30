<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Resources;

use Ux2Dev\Speedy\Dto\Request\Pickup\PickupRequest;
use Ux2Dev\Speedy\Dto\Request\Pickup\PickupTermsRequest;
use Ux2Dev\Speedy\Dto\Response\Pickup\PickupResponse;
use Ux2Dev\Speedy\Dto\Response\Pickup\PickupTermsResponse;
use Ux2Dev\Speedy\Resources\Resource;

final class Pickup extends Resource
{
    public function terms(PickupTermsRequest $request, ?string $language = null, ?int $clientSystemId = null): PickupTermsResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/pickup/terms', $body, PickupTermsResponse::class);
    }

    public function request(PickupRequest $request, ?string $language = null, ?int $clientSystemId = null): PickupResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/pickup', $body, PickupResponse::class);
    }
}
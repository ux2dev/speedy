<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Resources;

use Ux2Dev\Speedy\Dto\Request\Services\DestinationServicesRequest;
use Ux2Dev\Speedy\Dto\Response\Services\DestinationServicesResponse;
use Ux2Dev\Speedy\Resources\Resource;

final class Services extends Resource
{
    public function destinationServices(DestinationServicesRequest $request, ?string $language = null, ?int $clientSystemId = null): DestinationServicesResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/services/destination', $body, DestinationServicesResponse::class);
    }
}
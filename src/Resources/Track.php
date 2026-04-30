<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Resources;

use Ux2Dev\Speedy\Dto\Request\Track\TrackRequest;
use Ux2Dev\Speedy\Dto\Response\Track\TrackResponse;
use Ux2Dev\Speedy\Resources\Resource;

final class Track extends Resource
{
    public function track(TrackRequest $request, ?string $language = null, ?int $clientSystemId = null): TrackResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/track', $body, TrackResponse::class);
    }
}
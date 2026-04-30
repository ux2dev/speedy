<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Resources;

use Ux2Dev\Speedy\Dto\Request\Calculate\CalculationRequest;
use Ux2Dev\Speedy\Dto\Response\Calculate\CalculationResponse;
use Ux2Dev\Speedy\Resources\Resource;

final class Calculate extends Resource
{
    public function calculate(CalculationRequest $request, ?string $language = null, ?int $clientSystemId = null): CalculationResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/calculate', $body, CalculationResponse::class);
    }
}
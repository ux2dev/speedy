<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Resources;

use Ux2Dev\Speedy\Dto\Request\Validation\ValidateAddressRequest;
use Ux2Dev\Speedy\Dto\Response\Validation\ValidationResponse;
use Ux2Dev\Speedy\Resources\Resource;

final class Validation extends Resource
{
    public function validateAddress(ValidateAddressRequest $request, ?string $language = null, ?int $clientSystemId = null): ValidationResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/validation/address', $body, ValidationResponse::class);
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Resources;

use Ux2Dev\Speedy\Dto\Request\Payments\PayoutRequest;
use Ux2Dev\Speedy\Dto\Response\Payments\Payout;
use Ux2Dev\Speedy\Resources\Resource;

final class Payments extends Resource
{
    public function payouts(PayoutRequest $request, ?string $language = null, ?int $clientSystemId = null): Payout
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/payments', $body, Payout::class);
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Resources;

use Ux2Dev\Speedy\Dto\Request\PrintService\PrintVoucherRequest;
use Ux2Dev\Speedy\Http\PrintResult;
use Ux2Dev\Speedy\Resources\Resource;

final class PrintService extends Resource
{
    public function voucher(PrintVoucherRequest $request, ?string $language = null, ?int $clientSystemId = null): PrintResult
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postBinary('/print', $body);
    }
}
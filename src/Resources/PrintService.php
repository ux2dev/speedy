<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Resources;

use Ux2Dev\Speedy\Dto\Request\PrintService\LabelInfoRequest;
use Ux2Dev\Speedy\Dto\Request\PrintService\PrintRequest;
use Ux2Dev\Speedy\Dto\Request\PrintService\PrintVoucherRequest;
use Ux2Dev\Speedy\Dto\Response\PrintService\ExtendedPrintResponse;
use Ux2Dev\Speedy\Dto\Response\PrintService\LabelInfoResponse;
use Ux2Dev\Speedy\Dto\Response\PrintService\PrintVoucherResponse;
use Ux2Dev\Speedy\Resources\Resource;

final class PrintService extends Resource
{
    public function voucher(PrintVoucherRequest $request, ?string $language = null, ?int $clientSystemId = null): PrintVoucherResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/print', $body, PrintVoucherResponse::class);
    }

    public function extended(PrintRequest $request, ?string $language = null, ?int $clientSystemId = null): ExtendedPrintResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/print/extended', $body, ExtendedPrintResponse::class);
    }

    public function labelInfo(LabelInfoRequest $request, ?string $language = null, ?int $clientSystemId = null): LabelInfoResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/print/labelInfo', $body, LabelInfoResponse::class);
    }
}
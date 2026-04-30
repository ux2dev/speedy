<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Shipment;

final class FindParcelsByRefResponse
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?array $barcodes = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            barcodes: isset($data['barcodes']) && is_array($data['barcodes']) ? $data['barcodes'] : null,
        );
    }
}
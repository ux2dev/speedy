<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\PrintService;

final readonly class PrintVoucherRequest
{
    public function __construct(
        public readonly ?array $shipmentIds = null,
        public readonly ?string $printerName = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\PrintFormat $format = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\PrintDpi $dpi = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->shipmentIds !== null) $out['shipmentIds'] = $this->shipmentIds;
        if ($this->printerName !== null) $out['printerName'] = $this->printerName;
        if ($this->format !== null) $out['format'] = $this->format->toArray();
        if ($this->dpi !== null) $out['dpi'] = $this->dpi->toArray();
        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\PrintService;

final readonly class PrintVoucherRequest
{
    public function __construct(
        public readonly ?array $shipmentIds = null,
        public readonly ?string $printerName = null,
        public readonly ?array $format = null,
        public readonly ?array $dpi = null,
        public readonly ?string $paperSize = null,
        public readonly ?array $parcels = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->shipmentIds !== null) $out['shipmentIds'] = $this->shipmentIds;
        if ($this->printerName !== null) $out['printerName'] = $this->printerName;
        if ($this->format !== null) $out['format'] = $this->format;
        if ($this->dpi !== null) $out['dpi'] = $this->dpi;
        if ($this->paperSize !== null) $out['paperSize'] = $this->paperSize;
        if ($this->parcels !== null) $out['parcels'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\ParcelToPrint $x) => $x->toArray(), $this->parcels);
        return $out;
    }
}
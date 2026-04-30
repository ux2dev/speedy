<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\PrintService;

final readonly class PrintRequest
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\PrintFormat $format = null,
        public readonly ?string $paperSize = null,
        public readonly ?array $parcels = null,
        public readonly ?string $printerName = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\PrintDpi $dpi = null,
        public readonly ?string $additionalWaybillSenderCopy = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->format !== null) $out['format'] = $this->format->toArray();
        if ($this->paperSize !== null) $out['paperSize'] = $this->paperSize;
        if ($this->parcels !== null) $out['parcels'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\ParcelToPrint $x) => $x->toArray(), $this->parcels);
        if ($this->printerName !== null) $out['printerName'] = $this->printerName;
        if ($this->dpi !== null) $out['dpi'] = $this->dpi->toArray();
        if ($this->additionalWaybillSenderCopy !== null) $out['additionalWaybillSenderCopy'] = $this->additionalWaybillSenderCopy;
        return $out;
    }
}
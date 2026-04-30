<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class CODAdditionalServiceContractInfo
{
    public function __construct(
        public readonly ?bool $moneyTransferAllowed = null,
        public readonly ?bool $codFiscalReceiptAllowed = null,
        public readonly ?bool $hasCODAnnex = null,
        public readonly ?array $internationalCODAnnexes = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            moneyTransferAllowed: $data['moneyTransferAllowed'] ?? null,
            codFiscalReceiptAllowed: $data['codFiscalReceiptAllowed'] ?? null,
            hasCODAnnex: $data['hasCODAnnex'] ?? null,
            internationalCODAnnexes: isset($data['internationalCODAnnexes']) && is_array($data['internationalCODAnnexes']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\CODInternationalAnnexContractInfo::fromArray($r), $data['internationalCODAnnexes']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->moneyTransferAllowed !== null) $out['moneyTransferAllowed'] = $this->moneyTransferAllowed;
        if ($this->codFiscalReceiptAllowed !== null) $out['codFiscalReceiptAllowed'] = $this->codFiscalReceiptAllowed;
        if ($this->hasCODAnnex !== null) $out['hasCODAnnex'] = $this->hasCODAnnex;
        if ($this->internationalCODAnnexes !== null) $out['internationalCODAnnexes'] = array_map(fn(\Ux2Dev\Speedy\Dto\Model\CODInternationalAnnexContractInfo $x) => $x->toArray(), $this->internationalCODAnnexes);
        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class ReturnAmounts
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\MoneyTransferPremium $moneyTransfer = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            moneyTransfer: isset($data['moneyTransfer']) && is_array($data['moneyTransfer']) ? \Ux2Dev\Speedy\Dto\Model\MoneyTransferPremium::fromArray($data['moneyTransfer']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->moneyTransfer !== null) $out['moneyTransfer'] = $this->moneyTransfer->toArray();
        return $out;
    }
}
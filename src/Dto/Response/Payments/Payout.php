<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\Payments;

final class Payout
{
    public function __construct(
        public readonly ?string $date = null,
        public readonly ?int $docId = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\CODProcessingType $docType = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\PaymentType $paymentType = null,
        public readonly ?string $payee = null,
        public readonly ?string $currency = null,
        public readonly ?float $amount = null,
        public readonly ?array $details = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            date: $data['date'] ?? null,
            docId: $data['docId'] ?? null,
            docType: isset($data['docType']) && is_array($data['docType']) ? \Ux2Dev\Speedy\Dto\Model\CODProcessingType::fromArray($data['docType']) : null,
            paymentType: isset($data['paymentType']) && is_array($data['paymentType']) ? \Ux2Dev\Speedy\Dto\Model\PaymentType::fromArray($data['paymentType']) : null,
            payee: $data['payee'] ?? null,
            currency: $data['currency'] ?? null,
            amount: $data['amount'] ?? null,
            details: isset($data['details']) && is_array($data['details']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\PayoutDetails::fromArray($r), $data['details']) : null,
        );
    }
}
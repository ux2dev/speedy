<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Pickup;

final readonly class PickupTermsRequest
{
    public function __construct(
        public readonly ?int $startingDate = null,
        public readonly ?int $serviceId = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\CalculationSender $sender = null,
        public readonly ?bool $senderHasPayment = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->startingDate !== null) $out['startingDate'] = $this->startingDate;
        if ($this->serviceId !== null) $out['serviceId'] = $this->serviceId;
        if ($this->sender !== null) $out['sender'] = $this->sender->toArray();
        if ($this->senderHasPayment !== null) $out['senderHasPayment'] = $this->senderHasPayment;
        return $out;
    }
}
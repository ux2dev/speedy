<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Calculate;

final readonly class CalculationRequest
{
    public function __construct(
        public readonly ?\Ux2Dev\Speedy\Dto\Model\CalculationSender $sender = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\CalculationRecipient $recipient = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\CalculationService $service = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\CalculationContent $content = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\ShipmentPayment $payment = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->sender !== null) $out['sender'] = $this->sender->toArray();
        if ($this->recipient !== null) $out['recipient'] = $this->recipient->toArray();
        if ($this->service !== null) $out['service'] = $this->service->toArray();
        if ($this->content !== null) $out['content'] = $this->content->toArray();
        if ($this->payment !== null) $out['payment'] = $this->payment->toArray();
        return $out;
    }
}
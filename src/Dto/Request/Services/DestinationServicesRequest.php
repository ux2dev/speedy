<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Request\Services;

final readonly class DestinationServicesRequest
{
    public function __construct(
        public readonly ?int $date = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\CalculationSender $sender = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\CalculationRecipient $recipient = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->date !== null) $out['date'] = $this->date;
        if ($this->sender !== null) $out['sender'] = $this->sender->toArray();
        if ($this->recipient !== null) $out['recipient'] = $this->recipient->toArray();
        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class TrackedParcelOperationAdditionalInfo
{
    public function __construct(
        public readonly ?string $officeURL = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\TrackedParcelOperationAdditionalInfoRecipient $recipient = null,
        public readonly ?string $unsuccessfulDeliveryStickerImageURL = null,
        public readonly ?string $pickupOfficeType = null,
        public readonly ?string $geoPUDOId = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\TrackedParcelOperationAdditionalInfoPredict $predict = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            officeURL: $data['officeURL'] ?? null,
            recipient: isset($data['recipient']) && is_array($data['recipient']) ? \Ux2Dev\Speedy\Dto\Model\TrackedParcelOperationAdditionalInfoRecipient::fromArray($data['recipient']) : null,
            unsuccessfulDeliveryStickerImageURL: $data['unsuccessfulDeliveryStickerImageURL'] ?? null,
            pickupOfficeType: $data['pickupOfficeType'] ?? null,
            geoPUDOId: $data['geoPUDOId'] ?? null,
            predict: isset($data['predict']) && is_array($data['predict']) ? \Ux2Dev\Speedy\Dto\Model\TrackedParcelOperationAdditionalInfoPredict::fromArray($data['predict']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->officeURL !== null) $out['officeURL'] = $this->officeURL;
        if ($this->recipient !== null) $out['recipient'] = $this->recipient->toArray();
        if ($this->unsuccessfulDeliveryStickerImageURL !== null) $out['unsuccessfulDeliveryStickerImageURL'] = $this->unsuccessfulDeliveryStickerImageURL;
        if ($this->pickupOfficeType !== null) $out['pickupOfficeType'] = $this->pickupOfficeType;
        if ($this->geoPUDOId !== null) $out['geoPUDOId'] = $this->geoPUDOId;
        if ($this->predict !== null) $out['predict'] = $this->predict->toArray();
        return $out;
    }
}
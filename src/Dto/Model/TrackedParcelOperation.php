<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class TrackedParcelOperation
{
    public function __construct(
        public readonly ?string $dateTime = null,
        public readonly ?int $operationCode = null,
        public readonly ?string $place = null,
        public readonly ?string $description = null,
        public readonly ?string $comment = null,
        public readonly ?array $exceptionCodes = null,
        public readonly ?string $returnShipmentId = null,
        public readonly ?string $redirectShipmentId = null,
        public readonly ?string $consignee = null,
        public readonly ?string $podImageURL = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\TrackedParcelOperationAdditionalInfo $additionalInfo = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            dateTime: $data['dateTime'] ?? null,
            operationCode: $data['operationCode'] ?? null,
            place: $data['place'] ?? null,
            description: $data['description'] ?? null,
            comment: $data['comment'] ?? null,
            exceptionCodes: isset($data['exceptionCodes']) && is_array($data['exceptionCodes']) ? $data['exceptionCodes'] : null,
            returnShipmentId: $data['returnShipmentId'] ?? null,
            redirectShipmentId: $data['redirectShipmentId'] ?? null,
            consignee: $data['consignee'] ?? null,
            podImageURL: $data['podImageURL'] ?? null,
            additionalInfo: isset($data['additionalInfo']) && is_array($data['additionalInfo']) ? \Ux2Dev\Speedy\Dto\Model\TrackedParcelOperationAdditionalInfo::fromArray($data['additionalInfo']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->dateTime !== null) $out['dateTime'] = $this->dateTime;
        if ($this->operationCode !== null) $out['operationCode'] = $this->operationCode;
        if ($this->place !== null) $out['place'] = $this->place;
        if ($this->description !== null) $out['description'] = $this->description;
        if ($this->comment !== null) $out['comment'] = $this->comment;
        if ($this->exceptionCodes !== null) $out['exceptionCodes'] = $this->exceptionCodes;
        if ($this->returnShipmentId !== null) $out['returnShipmentId'] = $this->returnShipmentId;
        if ($this->redirectShipmentId !== null) $out['redirectShipmentId'] = $this->redirectShipmentId;
        if ($this->consignee !== null) $out['consignee'] = $this->consignee;
        if ($this->podImageURL !== null) $out['podImageURL'] = $this->podImageURL;
        if ($this->additionalInfo !== null) $out['additionalInfo'] = $this->additionalInfo->toArray();
        return $out;
    }
}
<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Response\PrintService;

final class ExtendedPrintResponse
{
    public function __construct(
        public readonly ?array $data = null,
        public readonly ?\Ux2Dev\Speedy\Dto\Model\Error $error = null,
        public readonly ?array $printLabelsInfo = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            data: isset($data['data']) && is_array($data['data']) ? $data['data'] : null,
            error: isset($data['error']) && is_array($data['error']) ? \Ux2Dev\Speedy\Dto\Model\Error::fromArray($data['error']) : null,
            printLabelsInfo: isset($data['printLabelsInfo']) && is_array($data['printLabelsInfo']) ? array_map(fn(array $r) => \Ux2Dev\Speedy\Dto\Model\LabelInfo::fromArray($r), $data['printLabelsInfo']) : null,
        );
    }
}
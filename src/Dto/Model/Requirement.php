<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class Requirement
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?string $text = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            text: $data['text'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->text !== null) $out['text'] = $this->text;
        return $out;
    }
}
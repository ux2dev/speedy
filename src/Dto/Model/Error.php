<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Dto\Model;

final class Error
{
    public function __construct(
        public readonly ?string $context = null,
        public readonly ?string $message = null,
        public readonly ?string $id = null,
        public readonly ?int $code = null,
        public readonly ?string $component = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            context: $data['context'] ?? null,
            message: $data['message'] ?? null,
            id: $data['id'] ?? null,
            code: $data['code'] ?? null,
            component: $data['component'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $out = [];
        if ($this->context !== null) $out['context'] = $this->context;
        if ($this->message !== null) $out['message'] = $this->message;
        if ($this->id !== null) $out['id'] = $this->id;
        if ($this->code !== null) $out['code'] = $this->code;
        if ($this->component !== null) $out['component'] = $this->component;
        return $out;
    }
}
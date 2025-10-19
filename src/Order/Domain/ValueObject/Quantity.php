<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

final class Quantity
{
    private function __construct(private readonly int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Quantity must be greater than zero.');
        }
    }

    public static function fromInt(int $value): self
    {
        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }
}

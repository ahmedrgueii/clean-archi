<?php

declare(strict_types=1);

namespace App\Product\Domain\ValueObject;

use App\Product\Domain\Exception\InsufficientStock;

final class Stock
{
    private function __construct(private readonly int $amount)
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Stock amount cannot be negative.');
        }
    }

    public static function fromInt(int $amount): self
    {
        return new self($amount);
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function increase(int $quantity): self
    {
        if ($quantity < 0) {
            throw new \InvalidArgumentException('Cannot increase stock with a negative quantity.');
        }

        return new self($this->amount + $quantity);
    }

    /**
     * @throws InsufficientStock
     */
    public function decrease(int $quantity): self
    {
        if ($quantity < 0) {
            throw new \InvalidArgumentException('Cannot decrease stock with a negative quantity.');
        }

        if ($quantity > $this->amount) {
            throw new InsufficientStock($this->amount, $quantity);
        }

        return new self($this->amount - $quantity);
    }
}

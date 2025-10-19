<?php

declare(strict_types=1);

namespace App\Product\Domain\ValueObject;

use App\Common\Domain\ValueObject\StringValue;

final class ProductName extends StringValue
{
    protected function __construct(string $value)
    {
        parent::__construct($value);
        $this->ensureIsNotEmpty($value);
    }

    private function ensureIsNotEmpty(string $value): void
    {
        if ('' === trim($value)) {
            throw new \InvalidArgumentException('Product name cannot be empty.');
        }
    }
}

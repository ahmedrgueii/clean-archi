<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject;

use App\Common\Domain\ValueObject\StringValue;

final class OrderStatus extends StringValue
{
    private const PENDING = 'pending';
    private const CONFIRMED = 'confirmed';
    private const CANCELLED = 'cancelled';

    protected function __construct(string $value)
    {
        parent::__construct($value);
        $this->ensureIsValid($value);
    }

    public static function pending(): self
    {
        return self::fromString(self::PENDING);
    }

    public static function confirmed(): self
    {
        return self::fromString(self::CONFIRMED);
    }

    public static function cancelled(): self
    {
        return self::fromString(self::CANCELLED);
    }

    private function ensureIsValid(string $value): void
    {
        if (!in_array($value, [self::PENDING, self::CONFIRMED, self::CANCELLED], true)) {
            throw new \InvalidArgumentException(sprintf('Order status "%s" is not supported.', $value));
        }
    }
}

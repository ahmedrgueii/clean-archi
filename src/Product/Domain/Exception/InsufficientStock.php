<?php

declare(strict_types=1);

namespace App\Product\Domain\Exception;

final class InsufficientStock extends \DomainException
{
    public function __construct(int $available, int $requested)
    {
        parent::__construct(sprintf('Insufficient stock. Available: %d, requested: %d.', $available, $requested));
    }
}

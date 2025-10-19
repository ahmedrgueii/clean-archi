<?php

declare(strict_types=1);

namespace App\Order\Domain\Exception;

final class EmptyOrder extends \DomainException
{
    public function __construct()
    {
        parent::__construct('An order must contain at least one item.');
    }
}

<?php

declare(strict_types=1);

namespace App\Order\Domain\Exception;

use App\Order\Domain\ValueObject\OrderId;

final class OrderNotFoundWithId extends OrderNotFound
{
    public function __construct(OrderId $id)
    {
        parent::__construct(sprintf('Order with id "%s" was not found.', (string) $id));
    }
}

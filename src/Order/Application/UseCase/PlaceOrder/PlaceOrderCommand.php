<?php

declare(strict_types=1);

namespace App\Order\Application\UseCase\PlaceOrder;

use App\Common\Application\Command\Command;

final class PlaceOrderCommand implements Command
{
    /**
     * @param array<int, array{productId:string, quantity:int}> $items
     */
    public function __construct(public readonly array $items)
    {
    }
}

<?php

declare(strict_types=1);

namespace App\Order\Domain\Repository;

use App\Common\Domain\Repository\Repository;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Exception\OrderNotFound;
use App\Order\Domain\ValueObject\OrderId;

/**
 * @extends Repository<Order>
 */
interface OrderRepository extends Repository
{
    public function add(Order $order): void;

    /**
     * @throws OrderNotFound
     */
    public function get(OrderId $id): Order;
}

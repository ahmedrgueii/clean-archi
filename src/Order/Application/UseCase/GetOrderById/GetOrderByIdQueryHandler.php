<?php

declare(strict_types=1);

namespace App\Order\Application\UseCase\GetOrderById;

use App\Common\Application\Query\QueryHandler;
use App\Order\Application\DTO\OrderDTO;
use App\Order\Domain\Exception\OrderNotFound;
use App\Order\Domain\Repository\OrderRepository;
use App\Order\Domain\ValueObject\OrderId;

final class GetOrderByIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly OrderRepository $orderRepository)
    {
    }

    /**
     * @throws OrderNotFound
     */
    public function __invoke(GetOrderByIdQuery $query): OrderDTO
    {
        $order = $this->orderRepository->get(OrderId::fromString($query->id));

        return OrderDTO::fromEntity($order);
    }
}

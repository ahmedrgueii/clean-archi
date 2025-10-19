<?php

declare(strict_types=1);

namespace App\Order\Application\UseCase\SearchOrdersPaginated;

use App\Common\Application\Query\QueryHandler;
use App\Order\Application\DTO\OrderDTO;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Repository\OrderRepository;

final class SearchOrdersPaginatedQueryHandler implements QueryHandler
{
    public function __construct(private readonly OrderRepository $orderRepository)
    {
    }

    /**
     * @return OrderDTO[]
     */
    public function __invoke(SearchOrdersPaginatedQuery $query): array
    {
        $orders = $this->orderRepository->search($query->page, $query->itemsPerPage);

        return $this->mapOrdersToOrderDTOs($orders);
    }

    /**
     * @param Order[] $orders
     * @return OrderDTO[]
     */
    private function mapOrdersToOrderDTOs(array $orders): array
    {
        return array_map(static fn (Order $order): OrderDTO => OrderDTO::fromEntity($order), $orders);
    }
}

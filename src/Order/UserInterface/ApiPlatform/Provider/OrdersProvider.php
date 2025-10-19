<?php

declare(strict_types=1);

namespace App\Order\UserInterface\ApiPlatform\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Common\Application\Query\QueryBus;
use App\Order\Application\DTO\OrderDTO;
use App\Order\Application\UseCase\SearchOrdersPaginated\SearchOrdersPaginatedQuery;
use App\Order\UserInterface\ApiPlatform\Resource\OrderResource;

/**
 * @template-implements ProviderInterface<OrderResource>
 */
final class OrdersProvider implements ProviderInterface
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly Pagination $pagination,
    ) {
    }

    /**
     * @return OrderResource[]
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $page = $this->pagination->getPage($context);
        $itemsPerPage = $this->pagination->getLimit($operation, $context);

        $orders = $this->queryBus->ask(new SearchOrdersPaginatedQuery($page, $itemsPerPage));

        return $this->mapOrdersToResources($orders);
    }

    /**
     * @param OrderDTO[] $orders
     * @return OrderResource[]
     */
    private function mapOrdersToResources(array $orders): array
    {
        return array_map(static fn (OrderDTO $order): OrderResource => OrderResource::fromOrderDTO($order), $orders);
    }
}

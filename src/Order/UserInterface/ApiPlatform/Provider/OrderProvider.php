<?php

declare(strict_types=1);

namespace App\Order\UserInterface\ApiPlatform\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Common\Application\Query\QueryBus;
use App\Order\Application\UseCase\GetOrderById\GetOrderByIdQuery;
use App\Order\UserInterface\ApiPlatform\Resource\OrderResource;

/**
 * @template-implements ProviderInterface<OrderResource>
 */
final class OrderProvider implements ProviderInterface
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?OrderResource
    {
        $orderDTO = $this->queryBus->ask(new GetOrderByIdQuery($uriVariables['id']));

        return OrderResource::fromOrderDTO($orderDTO);
    }
}

<?php

declare(strict_types=1);

namespace App\Order\UserInterface\ApiPlatform\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Common\Application\Command\CommandBus;
use App\Order\Application\UseCase\PlaceOrder\PlaceOrderCommand;
use App\Order\UserInterface\ApiPlatform\Resource\OrderItemResource;
use App\Order\UserInterface\ApiPlatform\Resource\OrderResource;

/**
 * @template-implements ProcessorInterface<OrderResource, OrderResource>
 */
final class CreateOrderProcessor implements ProcessorInterface
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): OrderResource
    {
        \assert($data instanceof OrderResource);

        $items = array_map(
            static function (mixed $item): array {
                if ($item instanceof OrderItemResource) {
                    return [
                        'productId' => $item->productId,
                        'quantity' => $item->quantity,
                    ];
                }

                return [
                    'productId' => $item['productId'] ?? '',
                    'quantity' => (int) ($item['quantity'] ?? 0),
                ];
            },
            $data->items
        );

        $orderDTO = $this->commandBus->dispatch(
            new PlaceOrderCommand(items: $items)
        );

        return OrderResource::fromOrderDTO($orderDTO);
    }
}

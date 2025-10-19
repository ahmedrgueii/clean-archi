<?php

declare(strict_types=1);

namespace App\Order\Application\UseCase\PlaceOrder;

use App\Common\Application\Command\CommandHandler;
use App\Order\Application\DTO\OrderDTO;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderItem;
use App\Order\Domain\Repository\OrderRepository;
use App\Order\Domain\ValueObject\Quantity;
use App\Product\Domain\Repository\ProductRepository;
use App\Product\Domain\ValueObject\ProductId;

final class PlaceOrderCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly OrderRepository $orderRepository,
    ) {
    }

    public function __invoke(PlaceOrderCommand $command): OrderDTO
    {
        $orderItems = [];

        foreach ($command->items as $item) {
            $productId = ProductId::fromString($item['productId']);
            $quantity = Quantity::fromInt($item['quantity']);

            $product = $this->productRepository->get($productId);
            $product->reserve($quantity->value());

            $orderItems[] = OrderItem::create(
                productId: $product->id(),
                productName: $product->name(),
                unitPrice: $product->price(),
                quantity: $quantity,
            );
        }

        $order = Order::place($orderItems);
        $this->orderRepository->add($order);

        return OrderDTO::fromEntity($order);
    }
}

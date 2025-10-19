<?php

declare(strict_types=1);

namespace App\Order\Application\DTO;

use App\Order\Domain\Entity\Order;

final class OrderDTO
{
    /**
     * @param OrderItemDTO[] $items
     */
    private function __construct(
        public readonly string $id,
        public readonly string $status,
        public readonly string $createdAt,
        public readonly int $totalAmount,
        public readonly string $totalCurrency,
        public readonly array $items,
    ) {
    }

    public static function fromEntity(Order $order): self
    {
        $items = array_map(static fn ($item) => OrderItemDTO::fromEntity($item), $order->items());

        return new self(
            id: (string) $order->id(),
            status: (string) $order->status(),
            createdAt: (string) $order->createdAt(),
            totalAmount: $order->total()->amount(),
            totalCurrency: $order->total()->currency(),
            items: $items,
        );
    }
}

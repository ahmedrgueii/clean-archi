<?php

declare(strict_types=1);

namespace App\Order\Application\DTO;

use App\Order\Domain\Entity\OrderItem;

final class OrderItemDTO
{
    private function __construct(
        public readonly string $id,
        public readonly string $productId,
        public readonly string $productName,
        public readonly int $quantity,
        public readonly int $unitPriceAmount,
        public readonly string $unitPriceCurrency,
        public readonly int $subtotalAmount,
    ) {
    }

    public static function fromEntity(OrderItem $item): self
    {
        return new self(
            id: (string) $item->id(),
            productId: (string) $item->productId(),
            productName: (string) $item->productName(),
            quantity: $item->quantity()->value(),
            unitPriceAmount: $item->unitPrice()->amount(),
            unitPriceCurrency: $item->unitPrice()->currency(),
            subtotalAmount: $item->subtotal()->amount(),
        );
    }
}

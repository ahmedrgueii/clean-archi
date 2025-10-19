<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Common\Domain\ValueObject\Money;
use App\Order\Domain\ValueObject\OrderItemId;
use App\Order\Domain\ValueObject\Quantity;
use App\Product\Domain\ValueObject\ProductId;
use App\Product\Domain\ValueObject\ProductName;

class OrderItem
{
    private OrderItemId $id;

    private ?Order $order = null;

    private Money $subtotal;

    private function __construct(
        private ProductId $productId,
        private ProductName $productName,
        private Money $unitPrice,
        private Quantity $quantity,
    ) {
        $this->id = OrderItemId::generate();
        $this->subtotal = $unitPrice->multiply($quantity->value());
    }

    public static function create(
        ProductId $productId,
        ProductName $productName,
        Money $unitPrice,
        Quantity $quantity,
    ): self {
        return new self($productId, $productName, $unitPrice, $quantity);
    }

    public function assignTo(Order $order): void
    {
        if (null !== $this->order) {
            throw new \LogicException('Order item is already assigned to an order.');
        }

        $this->order = $order;
    }

    public function id(): OrderItemId
    {
        return $this->id;
    }

    public function order(): ?Order
    {
        return $this->order;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function productName(): ProductName
    {
        return $this->productName;
    }

    public function unitPrice(): Money
    {
        return $this->unitPrice;
    }

    public function quantity(): Quantity
    {
        return $this->quantity;
    }

    public function subtotal(): Money
    {
        return $this->subtotal;
    }
}

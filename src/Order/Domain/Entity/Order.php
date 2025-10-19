<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Common\Domain\Entity\AggregateRoot;
use App\Common\Domain\ValueObject\DateTime;
use App\Common\Domain\ValueObject\Money;
use App\Order\Domain\Exception\EmptyOrder;
use App\Order\Domain\ValueObject\OrderId;
use App\Order\Domain\ValueObject\OrderStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Order extends AggregateRoot
{
    private OrderId $id;

    private DateTime $createdAt;

    private OrderStatus $status;

    /** @var Collection<int, OrderItem> */
    private Collection $items;

    private Money $total;

    private function __construct()
    {
        $this->id = OrderId::generate();
        $this->createdAt = DateTime::now();
        $this->status = OrderStatus::pending();
        $this->total = Money::zero();
        $this->items = new ArrayCollection();
    }

    /**
     * @param OrderItem[] $items
     */
    public static function place(array $items): self
    {
        if (0 === count($items)) {
            throw new EmptyOrder();
        }

        $order = new self();
        foreach ($items as $item) {
            $order->addItem($item);
        }

        return $order;
    }

    public function id(): OrderId
    {
        return $this->id;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function status(): OrderStatus
    {
        return $this->status;
    }

    /**
     * @return OrderItem[]
     */
    public function items(): array
    {
        return $this->items->toArray();
    }

    public function total(): Money
    {
        return $this->total;
    }

    public function confirm(): void
    {
        $this->status = OrderStatus::confirmed();
    }

    public function cancel(): void
    {
        $this->status = OrderStatus::cancelled();
    }

    private function addItem(OrderItem $item): void
    {
        if ($this->items->isEmpty()) {
            $this->total = Money::fromInt(0, $item->unitPrice()->currency());
        }

        $item->assignTo($this);
        $this->items->add($item);
        $this->total = $this->total->add($item->subtotal());
    }
}

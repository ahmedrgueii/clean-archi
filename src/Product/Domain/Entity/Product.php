<?php

declare(strict_types=1);

namespace App\Product\Domain\Entity;

use App\Common\Domain\Entity\AggregateRoot;
use App\Common\Domain\ValueObject\DateTime;
use App\Common\Domain\ValueObject\Money;
use App\Product\Domain\Exception\InsufficientStock;
use App\Product\Domain\ValueObject\ProductDescription;
use App\Product\Domain\ValueObject\ProductId;
use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\Stock;

class Product extends AggregateRoot
{
    private ProductId $id;

    private DateTime $createdAt;

    private ?DateTime $updatedAt = null;

    private function __construct(
        private ProductName $name,
        private ?ProductDescription $description,
        private Money $price,
        private Stock $stock,
    ) {
        $this->id = ProductId::generate();
        $this->createdAt = DateTime::now();
    }

    public static function create(
        ProductName $name,
        ?ProductDescription $description,
        Money $price,
        Stock $stock,
    ): self {
        return new self($name, $description, $price, $stock);
    }

    public function id(): ProductId
    {
        return $this->id;
    }

    public function name(): ProductName
    {
        return $this->name;
    }

    public function description(): ?ProductDescription
    {
        return $this->description;
    }

    public function price(): Money
    {
        return $this->price;
    }

    public function stock(): Stock
    {
        return $this->stock;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function changePrice(Money $price): void
    {
        $this->price = $price;
        $this->touch();
    }

    public function rename(ProductName $name, ?ProductDescription $description): void
    {
        $this->name = $name;
        $this->description = $description;
        $this->touch();
    }

    public function restock(int $quantity): void
    {
        $this->stock = $this->stock->increase($quantity);
        $this->touch();
    }

    /**
     * @throws InsufficientStock
     */
    public function reserve(int $quantity): void
    {
        $this->stock = $this->stock->decrease($quantity);
        $this->touch();
    }

    private function touch(): void
    {
        $this->updatedAt = DateTime::now();
    }
}

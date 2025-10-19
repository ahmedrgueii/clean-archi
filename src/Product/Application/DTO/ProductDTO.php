<?php

declare(strict_types=1);

namespace App\Product\Application\DTO;

use App\Product\Domain\Entity\Product;

final class ProductDTO
{
    private function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly int $priceAmount,
        public readonly string $priceCurrency,
        public readonly int $stock,
    ) {
    }

    public static function fromEntity(Product $product): self
    {
        return new self(
            id: (string) $product->id(),
            name: (string) $product->name(),
            description: null !== $product->description() ? (string) $product->description() : null,
            priceAmount: $product->price()->amount(),
            priceCurrency: $product->price()->currency(),
            stock: $product->stock()->amount(),
        );
    }
}

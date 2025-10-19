<?php

declare(strict_types=1);

namespace App\Product\Application\UseCase\CreateProduct;

use App\Common\Application\Command\CommandHandler;
use App\Common\Domain\ValueObject\Money;
use App\Product\Application\DTO\ProductDTO;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepository;
use App\Product\Domain\ValueObject\ProductDescription;
use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\Stock;

final class CreateProductCommandHandler implements CommandHandler
{
    public function __construct(private readonly ProductRepository $productRepository)
    {
    }

    public function __invoke(CreateProductCommand $command): ProductDTO
    {
        $product = Product::create(
            name: ProductName::fromString($command->name),
            description: null !== $command->description ? ProductDescription::fromString($command->description) : null,
            price: Money::fromInt($command->priceAmount, $command->priceCurrency),
            stock: Stock::fromInt($command->initialStock),
        );

        $this->productRepository->add($product);

        return ProductDTO::fromEntity($product);
    }
}

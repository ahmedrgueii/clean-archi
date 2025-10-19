<?php

declare(strict_types=1);

namespace App\Product\Application\UseCase\GetProductById;

use App\Common\Application\Query\QueryHandler;
use App\Product\Application\DTO\ProductDTO;
use App\Product\Domain\Exception\ProductNotFound;
use App\Product\Domain\Repository\ProductRepository;
use App\Product\Domain\ValueObject\ProductId;

final class GetProductByIdQueryHandler implements QueryHandler
{
    public function __construct(private readonly ProductRepository $productRepository)
    {
    }

    /**
     * @throws ProductNotFound
     */
    public function __invoke(GetProductByIdQuery $query): ProductDTO
    {
        $product = $this->productRepository->get(ProductId::fromString($query->id));

        return ProductDTO::fromEntity($product);
    }
}

<?php

declare(strict_types=1);

namespace App\Product\Application\UseCase\SearchProductsPaginated;

use App\Common\Application\Query\QueryHandler;
use App\Product\Application\DTO\ProductDTO;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepository;

final class SearchProductsPaginatedQueryHandler implements QueryHandler
{
    public function __construct(private readonly ProductRepository $productRepository)
    {
    }

    /**
     * @return ProductDTO[]
     */
    public function __invoke(SearchProductsPaginatedQuery $query): array
    {
        $products = $this->productRepository->search($query->page, $query->itemsPerPage);

        return $this->mapProductsToProductDTOs($products);
    }

    /**
     * @param Product[] $products
     * @return ProductDTO[]
     */
    private function mapProductsToProductDTOs(array $products): array
    {
        return array_map(static fn (Product $product): ProductDTO => ProductDTO::fromEntity($product), $products);
    }
}

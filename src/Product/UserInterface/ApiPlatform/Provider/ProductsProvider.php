<?php

declare(strict_types=1);

namespace App\Product\UserInterface\ApiPlatform\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\Common\Application\Query\QueryBus;
use App\Product\Application\DTO\ProductDTO;
use App\Product\Application\UseCase\SearchProductsPaginated\SearchProductsPaginatedQuery;
use App\Product\UserInterface\ApiPlatform\Resource\ProductResource;

/**
 * @template-implements ProviderInterface<ProductResource>
 */
final class ProductsProvider implements ProviderInterface
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly Pagination $pagination,
    ) {
    }

    /**
     * @return ProductResource[]
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $page = $this->pagination->getPage($context);
        $itemsPerPage = $this->pagination->getLimit($operation, $context);

        $products = $this->getProducts($page, $itemsPerPage);

        return $this->mapProductsToResources($products);
    }

    /**
     * @return ProductDTO[]
     */
    private function getProducts(int $page, int $itemsPerPage): array
    {
        return $this->queryBus->ask(new SearchProductsPaginatedQuery($page, $itemsPerPage));
    }

    /**
     * @param ProductDTO[] $products
     * @return ProductResource[]
     */
    private function mapProductsToResources(array $products): array
    {
        return array_map(static fn (ProductDTO $product): ProductResource => ProductResource::fromProductDTO($product), $products);
    }
}

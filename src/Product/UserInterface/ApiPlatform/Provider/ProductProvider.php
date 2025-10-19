<?php

declare(strict_types=1);

namespace App\Product\UserInterface\ApiPlatform\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Common\Application\Query\QueryBus;
use App\Product\Application\UseCase\GetProductById\GetProductByIdQuery;
use App\Product\UserInterface\ApiPlatform\Resource\ProductResource;

/**
 * @template-implements ProviderInterface<ProductResource>
 */
final class ProductProvider implements ProviderInterface
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?ProductResource
    {
        $productDTO = $this->queryBus->ask(new GetProductByIdQuery($uriVariables['id']));

        return ProductResource::fromProductDTO($productDTO);
    }
}

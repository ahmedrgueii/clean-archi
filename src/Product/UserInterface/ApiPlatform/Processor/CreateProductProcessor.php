<?php

declare(strict_types=1);

namespace App\Product\UserInterface\ApiPlatform\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Common\Application\Command\CommandBus;
use App\Product\Application\UseCase\CreateProduct\CreateProductCommand;
use App\Product\UserInterface\ApiPlatform\Resource\ProductResource;

/**
 * @template-implements ProcessorInterface<ProductResource, ProductResource>
 */
final class CreateProductProcessor implements ProcessorInterface
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ProductResource
    {
        \assert($data instanceof ProductResource);

        $productDTO = $this->commandBus->dispatch(
            new CreateProductCommand(
                name: $data->name,
                description: $data->description,
                priceAmount: $data->priceAmount,
                priceCurrency: $data->priceCurrency,
                initialStock: $data->stock,
            )
        );

        return ProductResource::fromProductDTO($productDTO);
    }
}

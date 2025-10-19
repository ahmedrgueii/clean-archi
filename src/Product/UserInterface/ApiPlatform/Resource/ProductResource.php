<?php

declare(strict_types=1);

namespace App\Product\UserInterface\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Product\Application\DTO\ProductDTO;
use App\Product\UserInterface\ApiPlatform\Processor\CreateProductProcessor;
use App\Product\UserInterface\ApiPlatform\Provider\ProductProvider;
use App\Product\UserInterface\ApiPlatform\Provider\ProductsProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Product',
    operations: [
        new GetCollection(
            openapiContext: ['summary' => 'Search products'],
            provider: ProductsProvider::class,
        ),
        new Get(
            openapiContext: ['summary' => 'Get product'],
            provider: ProductProvider::class,
        ),
        new Post(
            openapiContext: ['summary' => 'Create product'],
            denormalizationContext: ['groups' => ['create']],
            validationContext: ['groups' => ['create']],
            processor: CreateProductProcessor::class,
        ),
    ],
)]
final class ProductResource
{
    public function __construct(
        #[ApiProperty(readable: true, writable: false, identifier: true)]
        #[Groups(groups: ['read'])]
        public readonly string $id = '',

        #[Assert\NotBlank(groups: ['create'])]
        #[Assert\Length(min: 1, max: 255)]
        #[Groups(groups: ['read', 'create'])]
        public readonly string $name = '',

        #[Assert\Length(max: 1024)]
        #[Groups(groups: ['read', 'create'])]
        public readonly ?string $description = null,

        #[Assert\NotNull(groups: ['create'])]
        #[Assert\GreaterThanOrEqual(0)]
        #[Groups(groups: ['read', 'create'])]
        public readonly int $priceAmount = 0,

        #[Assert\NotBlank(groups: ['create'])]
        #[Assert\Currency]
        #[Groups(groups: ['read', 'create'])]
        public readonly string $priceCurrency = 'EUR',

        #[Assert\NotNull(groups: ['create'])]
        #[Assert\GreaterThanOrEqual(0)]
        #[Groups(groups: ['read', 'create'])]
        public readonly int $stock = 0,
    ) {
    }

    public static function fromProductDTO(ProductDTO $dto): self
    {
        return new self(
            id: $dto->id,
            name: $dto->name,
            description: $dto->description,
            priceAmount: $dto->priceAmount,
            priceCurrency: $dto->priceCurrency,
            stock: $dto->stock,
        );
    }
}

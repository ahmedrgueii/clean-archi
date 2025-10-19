<?php

declare(strict_types=1);

namespace App\Order\UserInterface\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Order\Application\DTO\OrderDTO;
use App\Order\Application\DTO\OrderItemDTO;
use App\Order\UserInterface\ApiPlatform\Processor\CreateOrderProcessor;
use App\Order\UserInterface\ApiPlatform\Provider\OrderProvider;
use App\Order\UserInterface\ApiPlatform\Provider\OrdersProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Order',
    operations: [
        new GetCollection(
            openapiContext: ['summary' => 'Search orders'],
            provider: OrdersProvider::class,
        ),
        new Get(
            openapiContext: ['summary' => 'Get order'],
            provider: OrderProvider::class,
        ),
        new Post(
            openapiContext: ['summary' => 'Place order'],
            denormalizationContext: ['groups' => ['create']],
            validationContext: ['groups' => ['create']],
            processor: CreateOrderProcessor::class,
        ),
    ],
)]
final class OrderResource
{
    /**
     * @param OrderItemResource[] $items
     */
    public function __construct(
        #[ApiProperty(readable: true, writable: false, identifier: true)]
        #[Groups(groups: ['read'])]
        public readonly string $id = '',

        #[Groups(groups: ['read'])]
        public readonly string $status = '',

        #[Groups(groups: ['read'])]
        public readonly string $createdAt = '',

        #[Groups(groups: ['read'])]
        public readonly int $totalAmount = 0,

        #[Groups(groups: ['read'])]
        public readonly string $totalCurrency = 'EUR',

        #[Assert\NotBlank(groups: ['create'])]
        #[Assert\Count(min: 1, groups: ['create'])]
        #[Assert\Valid]
        #[Groups(groups: ['read', 'create'])]
        /** @var OrderItemResource[] */
        public readonly array $items = [],
    ) {
    }

    public static function fromOrderDTO(OrderDTO $dto): self
    {
        $items = array_map(static fn (OrderItemDTO $item): OrderItemResource => OrderItemResource::fromOrderItemDTO($item), $dto->items);

        return new self(
            id: $dto->id,
            status: $dto->status,
            createdAt: $dto->createdAt,
            totalAmount: $dto->totalAmount,
            totalCurrency: $dto->totalCurrency,
            items: $items,
        );
    }
}

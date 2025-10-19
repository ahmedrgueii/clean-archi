<?php

declare(strict_types=1);

namespace App\Order\UserInterface\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use App\Order\Application\DTO\OrderItemDTO;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class OrderItemResource
{
    public function __construct(
        #[ApiProperty(readable: true, writable: false)]
        #[Groups(groups: ['read'])]
        public readonly string $id = '',

        #[Assert\NotBlank(groups: ['create'])]
        #[Groups(groups: ['read', 'create'])]
        public readonly string $productId = '',

        #[Groups(groups: ['read'])]
        public readonly string $productName = '',

        #[Assert\NotNull(groups: ['create'])]
        #[Assert\GreaterThan(0, groups: ['create'])]
        #[Groups(groups: ['read', 'create'])]
        public readonly int $quantity = 1,

        #[Groups(groups: ['read'])]
        public readonly int $unitPriceAmount = 0,

        #[Groups(groups: ['read'])]
        public readonly string $unitPriceCurrency = 'EUR',

        #[Groups(groups: ['read'])]
        public readonly int $subtotalAmount = 0,
    ) {
    }

    public static function fromOrderItemDTO(OrderItemDTO $dto): self
    {
        return new self(
            id: $dto->id,
            productId: $dto->productId,
            productName: $dto->productName,
            quantity: $dto->quantity,
            unitPriceAmount: $dto->unitPriceAmount,
            unitPriceCurrency: $dto->unitPriceCurrency,
            subtotalAmount: $dto->subtotalAmount,
        );
    }
}

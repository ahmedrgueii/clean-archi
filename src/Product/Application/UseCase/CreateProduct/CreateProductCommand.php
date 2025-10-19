<?php

declare(strict_types=1);

namespace App\Product\Application\UseCase\CreateProduct;

use App\Common\Application\Command\Command;

final class CreateProductCommand implements Command
{
    /**
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly int $priceAmount,
        public readonly string $priceCurrency,
        public readonly int $initialStock,
        public readonly array $metadata = [],
    ) {
    }
}

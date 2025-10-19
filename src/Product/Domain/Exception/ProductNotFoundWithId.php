<?php

declare(strict_types=1);

namespace App\Product\Domain\Exception;

use App\Product\Domain\ValueObject\ProductId;

final class ProductNotFoundWithId extends ProductNotFound
{
    public function __construct(ProductId $id)
    {
        parent::__construct(sprintf('Product with id "%s" was not found.', (string) $id));
    }
}

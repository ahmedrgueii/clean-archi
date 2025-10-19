<?php

declare(strict_types=1);

namespace App\Product\Domain\Repository;

use App\Common\Domain\Repository\Repository;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Exception\ProductNotFound;
use App\Product\Domain\ValueObject\ProductId;

/**
 * @extends Repository<Product>
 */
interface ProductRepository extends Repository
{
    public function add(Product $product): void;

    /**
     * @throws ProductNotFound
     */
    public function get(ProductId $id): Product;

    /**
     * @return Product[]
     */
    public function search(int $pageNumber, int $itemsPerPage): array;
}

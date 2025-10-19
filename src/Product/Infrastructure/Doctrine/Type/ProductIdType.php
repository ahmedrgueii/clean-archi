<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Doctrine\Type;

use App\Common\Infrastructure\Doctrine\Type\UuidType;

final class ProductIdType extends UuidType
{
    public const TYPE = 'product_id';
}

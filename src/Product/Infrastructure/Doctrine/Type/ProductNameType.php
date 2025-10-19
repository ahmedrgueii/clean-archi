<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Doctrine\Type;

use App\Common\Infrastructure\Doctrine\Type\StringType;
use App\Product\Domain\ValueObject\ProductName;
use Doctrine\DBAL\Platforms\AbstractPlatform;

final class ProductNameType extends StringType
{
    public const TYPE = 'product_name';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ProductName
    {
        if (null === $value) {
            return null;
        }

        return ProductName::fromString((string) $value);
    }
}

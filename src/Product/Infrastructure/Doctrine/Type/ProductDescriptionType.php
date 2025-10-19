<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Doctrine\Type;

use App\Common\Infrastructure\Doctrine\Type\TextType;
use App\Product\Domain\ValueObject\ProductDescription;
use Doctrine\DBAL\Platforms\AbstractPlatform;

final class ProductDescriptionType extends TextType
{
    public const TYPE = 'product_description';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ProductDescription
    {
        if (null === $value) {
            return null;
        }

        return ProductDescription::fromString((string) $value);
    }
}

<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Doctrine\Type;

use App\Product\Domain\ValueObject\Stock;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\IntegerType;

final class StockType extends IntegerType
{
    public const TYPE = 'stock';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Stock) {
            throw ConversionException::conversionFailedInvalidType($value, self::TYPE, [Stock::class, 'null']);
        }

        return $value->amount();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Stock
    {
        if (null === $value || $value instanceof Stock) {
            return $value;
        }

        return Stock::fromInt((int) $value);
    }

    public function getName(): string
    {
        return self::TYPE;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}

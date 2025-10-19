<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Doctrine\Type;

use App\Order\Domain\ValueObject\Quantity;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\IntegerType;

final class QuantityType extends IntegerType
{
    public const TYPE = 'quantity';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Quantity) {
            throw ConversionException::conversionFailedInvalidType($value, self::TYPE, [Quantity::class, 'null']);
        }

        return $value->value();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Quantity
    {
        if (null === $value || $value instanceof Quantity) {
            return $value;
        }

        return Quantity::fromInt((int) $value);
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

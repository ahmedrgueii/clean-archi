<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Doctrine\Type;

use App\Common\Domain\ValueObject\Money;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;

class MoneyType extends JsonType
{
    public const TYPE = 'money';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Money) {
            throw ConversionException::conversionFailedInvalidType($value, self::TYPE, [Money::class, 'null']);
        }

        return parent::convertToDatabaseValue($value->toArray(), $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Money
    {
        if (null === $value || $value instanceof Money) {
            return $value;
        }

        /** @var array{amount:int,currency:string} $decoded */
        $decoded = parent::convertToPHPValue($value, $platform);

        return Money::fromArray($decoded);
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

<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Doctrine\Type;

use App\Common\Infrastructure\Doctrine\Type\StringType;
use App\Order\Domain\ValueObject\OrderStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;

final class OrderStatusType extends StringType
{
    public const TYPE = 'order_status';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?OrderStatus
    {
        if (null === $value) {
            return null;
        }

        return OrderStatus::fromString((string) $value);
    }
}

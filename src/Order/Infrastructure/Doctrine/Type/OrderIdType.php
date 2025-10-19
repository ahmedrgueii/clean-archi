<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Doctrine\Type;

use App\Common\Infrastructure\Doctrine\Type\UuidType;

final class OrderIdType extends UuidType
{
    public const TYPE = 'order_id';
}

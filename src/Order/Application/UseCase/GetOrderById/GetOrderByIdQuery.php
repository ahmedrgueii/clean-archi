<?php

declare(strict_types=1);

namespace App\Order\Application\UseCase\GetOrderById;

use App\Common\Application\Query\Query;

final class GetOrderByIdQuery implements Query
{
    public function __construct(public readonly string $id)
    {
    }
}

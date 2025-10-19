<?php

declare(strict_types=1);

namespace App\Product\Application\UseCase\GetProductById;

use App\Common\Application\Query\Query;

final class GetProductByIdQuery implements Query
{
    public function __construct(public readonly string $id)
    {
    }
}

<?php

declare(strict_types=1);

namespace App\Order\Application\UseCase\SearchOrdersPaginated;

use App\Common\Application\Query\Query;

final class SearchOrdersPaginatedQuery implements Query
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $itemsPerPage = 20,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace App\Product\Application\UseCase\SearchProductsPaginated;

use App\Common\Application\Query\Query;

final class SearchProductsPaginatedQuery implements Query
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $itemsPerPage = 20,
    ) {
    }
}

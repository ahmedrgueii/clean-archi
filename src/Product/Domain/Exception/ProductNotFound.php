<?php

declare(strict_types=1);

namespace App\Product\Domain\Exception;

use App\Common\Domain\Exception\ResourceNotFound;

abstract class ProductNotFound extends ResourceNotFound
{
}

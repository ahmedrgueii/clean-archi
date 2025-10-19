<?php

declare(strict_types=1);

namespace App\Order\Domain\Exception;

use App\Common\Domain\Exception\ResourceNotFound;

abstract class OrderNotFound extends ResourceNotFound
{
}

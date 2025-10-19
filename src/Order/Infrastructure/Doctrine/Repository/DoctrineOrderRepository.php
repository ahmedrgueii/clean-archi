<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Doctrine\Repository;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Exception\OrderNotFound;
use App\Order\Domain\Exception\OrderNotFoundWithId;
use App\Order\Domain\Repository\OrderRepository;
use App\Order\Domain\ValueObject\OrderId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends ServiceEntityRepository<Order>
 */
final class DoctrineOrderRepository extends ServiceEntityRepository implements OrderRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function add(Order $order): void
    {
        $this->getEntityManager()->persist($order);
    }

    /**
     * @throws OrderNotFound
     */
    public function get(OrderId $id): Order
    {
        /** @var ?Order $order */
        $order = $this->find($id);
        if (null === $order) {
            throw new OrderNotFoundWithId($id);
        }

        return $order;
    }

    public function search(int $pageNumber, int $itemsPerPage): array
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->setFirstResult(($pageNumber - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage)
            ->orderBy(new OrderBy('o.createdAt', 'DESC'));

        return $queryBuilder->getQuery()->getResult();
    }
}

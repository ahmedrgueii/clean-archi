<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Doctrine\Repository;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Exception\ProductNotFound;
use App\Product\Domain\Exception\ProductNotFoundWithId;
use App\Product\Domain\Repository\ProductRepository;
use App\Product\Domain\ValueObject\ProductId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends ServiceEntityRepository<Product>
 */
final class DoctrineProductRepository extends ServiceEntityRepository implements ProductRepository
{
    private const ALIAS = 'product';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $product): void
    {
        $this->getEntityManager()->persist($product);
    }

    /**
     * @throws ProductNotFound
     */
    public function get(ProductId $id): Product
    {
        /** @var ?Product $product */
        $product = $this->find($id);
        if (null === $product) {
            throw new ProductNotFoundWithId($id);
        }

        return $product;
    }

    public function search(int $pageNumber, int $itemsPerPage): array
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);

        $queryBuilder
            ->setFirstResult(($pageNumber - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage)
            ->orderBy(new OrderBy(self::ALIAS . '.createdAt', 'DESC'));

        return $queryBuilder->getQuery()->getResult();
    }
}

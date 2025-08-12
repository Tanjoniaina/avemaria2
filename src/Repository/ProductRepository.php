<?php

namespace App\Repository;

use App\Pharmaciegros\Entity\Product;
use App\Shared\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findBelowMinimumByLocation(Location $location): array
    {
        $positiveTypes = ['ENTREE'];

        // LEFT JOIN movements to include products without any movement (sum = null → coalesce to 0)
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('App\Pharmaciegros\Entity\Stockmovement', 'm', 'WITH', 'm.product = p AND m.location = :location')
            ->addSelect('COALESCE(SUM(CASE WHEN m.type IN (:pos) THEN m.quantity ELSE -m.quantity END), 0) AS currentStock')
            ->andWhere('COALESCE(p.stockmin, 0) > 0') // optional: only consider products with a min
            ->groupBy('p.id')
            ->having('COALESCE(SUM(CASE WHEN m.type IN (:pos) THEN m.quantity ELSE -m.quantity END), 0) < COALESCE(p.stockmin, 0)')
            ->setParameter('location', $location)
            ->setParameter('pos', $positiveTypes);

        /** @var array<int, array{0: Product, currentStock: string}> $rows */
        $rows = $qb->getQuery()->getResult();

        return array_map(static function (array $row) {
            /** @var Product $product */
            $product = $row[0];
            $min = method_exists($product, 'getStockmin') ? (int) $product->getStockmin() : 0;

            return [
                'product' => $product,
                'currentStock' => (int) $row['currentStock'],
                'minStock' => $min,
            ];
        }, $rows);
    }


//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

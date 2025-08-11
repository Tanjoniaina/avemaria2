<?php

namespace App\Repository;

use App\Pharmaciegros\Entity\Product;
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

    public function findProduitsSousStockMin(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id, p.name, p.stockmin, COALESCE(SUM(
            CASE 
                WHEN m.type = :entree THEN m.quantity
                WHEN m.type = :sortie THEN -m.quantity
                ELSE 0
            END
        ), 0) AS stockActuel')
            ->leftJoin('p.stockmovement', 'm')
            ->groupBy('p.id, p.name, p.stockmin')
            ->having('stockActuel <= p.stockmin')
            ->setParameter('entree', 'ENTREE')
            ->setParameter('sortie', 'SORTIE');

        return $qb->getQuery()->getResult();
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

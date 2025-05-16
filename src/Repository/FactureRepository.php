<?php

namespace App\Repository;

use App\Facturation\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Facture>
 */
class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }

    /**
     * Récupère une facture avec ses lignes en un seul coup.
     */
    public function findAvecLignes(int $id): ?Facture
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.ligne', 'ligne')       // jointure
            ->addSelect('ligne')                 // indique qu'on veut aussi les données des lignes
            ->where('f.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findLastFacture(): ?Facture
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
//    /**
//     * @return Facture[] Returns an array of Facture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Facture
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Shared\Entity\Dossierpatient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dossierpatient>
 */
class DossierpatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dossierpatient::class);
    }

    public function findEnFacturation(array $etats, string $parcours): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.datefin IS NULL')
            ->andWhere('d.etatparcours IN (:etats)')
            ->andWhere('d.typeparcours = :parcours')
            ->setParameter('etats', $etats)
            ->setParameter('parcours', $parcours)
            ->orderBy('d.datedebut', 'DESC')
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Dossierpatient[] Returns an array of Dossierpatient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Dossierpatient
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

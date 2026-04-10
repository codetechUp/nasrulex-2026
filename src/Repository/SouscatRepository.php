<?php

namespace App\Repository;

use App\Entity\Souscat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Souscat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Souscat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Souscat[]    findAll()
 * @method Souscat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SouscatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Souscat::class);
    }

    // /**
    //  * @return Souscat[] Returns an array of Souscat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Souscat
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

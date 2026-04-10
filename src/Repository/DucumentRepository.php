<?php

namespace App\Repository;

use App\Entity\Ducument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ducument|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ducument|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ducument[]    findAll()
 * @method Ducument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DucumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ducument::class);
    }

    // /**
    //  * @return Ducument[] Returns an array of Ducument objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ducument
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.libelle = :val')
            ->setParameter('val', $value)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
}

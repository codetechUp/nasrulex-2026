<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }



    public function searchDoc($mots, $cat, $scat = null, $date = null)
{
    $qb = $this->createQueryBuilder('d');
    
    // Recherche par mots clés - version étendue
    if (!empty($mots)) {
        $mots = trim($mots);
        $mots = preg_replace('/\s+/', ' ', $mots);
        
        $keywords = explode(' ', $mots);
        $orX = $qb->expr()->orX();
        
        foreach ($keywords as $index => $keyword) {
            $keyword = trim($keyword);
            if (strlen($keyword) > 1) { // Recherche même avec 2 caractères
                $param = ':keyword_' . $index;
                
                // Recherche insensible à la casse et aux accents
                $orX->add($qb->expr()->like('LOWER(d.description)', 'LOWER(' . $param . ')'));
                $qb->setParameter($param, '%' . $keyword . '%');
                
                // Si le mot fait plus de 3 caractères, recherche également les débuts de mots
                if (strlen($keyword) > 3) {
                    $paramStart = ':keyword_start_' . $index;
                    $orX->add($qb->expr()->like('LOWER(d.description)', 'LOWER(' . $paramStart . ')'));
                    $qb->setParameter($paramStart, $keyword . '%');
                }
            }
        }
        
        if ($orX->count() > 0) {
            $qb->andWhere($orX);
        }
    }
    
    // Gestion des catégories et sous-catégories
    if (!empty($cat)) {
        if (strpos($cat, '-') !== false) {
            $parts = explode('-', $cat, 2);
            $categorieId = $parts[0];
            $sousCategorieId = isset($parts[1]) ? $parts[1] : null;
            
            $qb->andWhere('d.categorie = :categorieId')
               ->setParameter('categorieId', $categorieId);
            
            if (!empty($sousCategorieId)) {
                $qb->andWhere('d.souscat = :sousCategorieId')
                   ->setParameter('sousCategorieId', $sousCategorieId);
            }
        } else {
            $qb->andWhere('d.categorie = :cat')
               ->setParameter('cat', $cat);
        }
    }
    
    // Sous-catégorie indépendante
    if (!empty($scat)) {
        $qb->andWhere('d.souscat = :scat')
           ->setParameter('scat', $scat);
    }
    
    // Gestion de la date
    if (!empty($date)) {
        if (is_string($date)) {
            try {
                $dateObj = new \DateTime($date);
                $qb->andWhere('DATE(d.CreatedAt) = :date')
                   ->setParameter('date', $dateObj->format('Y-m-d'));
            } catch (\Exception $e) {
                // Logger l'erreur si nécessaire
            }
        } else if ($date instanceof \DateTime) {
            $qb->andWhere('DATE(d.CreatedAt) = :date')
               ->setParameter('date', $date->format('Y-m-d'));
        }
    }
    
    // Tri par date de création décroissante
    $qb->orderBy('d.CreatedAt', 'DESC');
    
    return $qb->getQuery()->getResult();
}



    
    public function findByCat($value)
{
    return $this->createQueryBuilder('d')
        ->andWhere('d.categorie = :val')
        ->setParameter('val', $value)
        ->orderBy('d.id', 'ASC')
        ->getQuery()
        ->getResult()
    ;
}
public function findCount($value)
{
    return $this->createQueryBuilder('d')
        ->select('count(d.id)')
        ->andWhere('d.categorie = :val')
        ->setParameter('val', $value)
        ->getQuery()
        ->getResult()
    ;
}


    // /**
    //  * @return Document[] Returns an array of Document objects
    //  */
    /*
    public function findByCat($value)
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
    public function findOneBySomeField($value): ?Document
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\Annale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Annale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annale[]    findAll()
 * @method Annale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annale::class);
    }

    // /**
    //  * @return Annale[] Returns an array of Annale objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Annale
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

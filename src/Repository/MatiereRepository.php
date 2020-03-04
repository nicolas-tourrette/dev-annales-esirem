<?php

namespace App\Repository;

use App\Entity\Matiere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Matiere|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matiere|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matiere[]    findAll()
 * @method Matiere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatiereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matiere::class);
    }

    public function getMatiereByDptYear($dpt, $year){
        $qb = $this
            ->createQueryBuilder('m')
            ->andWhere('m.departement = :dpt')
            ->setParameter('dpt', $dpt)
            ->andWhere('m.annee = :year')
            ->setParameter('year', $year)
            ->orderBy('m.nom', 'ASC')
        ;
        
        return $qb->getQuery()->getResult();
    }

    public function getMatiere($dpt, $year, $id){
        $qb = $this
            ->createQueryBuilder('m')
            ->andWhere('m.id = :id')
            ->setParameter('id', $id)
            ->andWhere('m.departement = :dpt')
            ->setParameter('dpt', $dpt)
            ->andWhere('m.annee = :year')
            ->setParameter('year', $year)
            ->orderBy('m.nom', 'ASC')
        ;
        
        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Matiere[] Returns an array of Matiere objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Matiere
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

/**
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function getNotificationsOlderThan(\Datetime $date){
        $qb = $this
            ->createQueryBuilder('n')
            ->where('n.date <= :date')
            ->setParameter('date', $date)
        ;

        return $qb->getQuery()->getResult();
    }

    public function findMyNotifications($user, $group, $role, $limit)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.recipient = :groupe')
            ->orWhere('n.recipient = \'all\'')
            ->orWhere('n.recipient = :user')
            ->orWhere('n.recipient = :role')
            ->setParameter('groupe', $group)
            ->setParameter('user', $user)
            ->setParameter('role', $role)
            ->orderBy('n.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findMyNotificationsNumber($user, $group, $role)
    {
        return $this->createQueryBuilder('n')
            ->select("count(n)")
            ->andWhere('n.recipient = :groupe')
            ->orWhere('n.recipient = \'all\'')
            ->orWhere('n.recipient = :user')
            ->orWhere('n.recipient = :role')
            ->setParameter('groupe', $group)
            ->setParameter('user', $user)
            ->setParameter('role', $role)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function findAllMyNotifications($user, $group, $role)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.recipient = :groupe')
            ->orWhere('n.recipient = \'all\'')
            ->orWhere('n.recipient = :user')
            ->orWhere('n.recipient = :role')
            ->setParameter('groupe', $group)
            ->setParameter('user', $user)
            ->setParameter('role', $role)
            ->orderBy('n.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Notification[] Returns an array of Notification objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Notification
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

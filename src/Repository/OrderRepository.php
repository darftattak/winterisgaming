<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findByUser($user) {
        $stmt = $this->createQueryBuilder('o');
        $stmt->where("o.user = :user");
        $stmt->setParameter("user", $user);

        return $stmt->getQuery()->getResult();
    }

    public function findByStatus(int $status) {
        $stmt = $this->createQueryBuilder('o');
        $stmt->where("o.status = :status");
        $stmt->setParameter("status", $status);

        return $stmt->getQuery()->getResult();
    }

    public function countByStatus(int $status) {
        $stmt = $this->createQueryBuilder('o');
        $stmt->select("count(o.id)");
        $stmt->where("o.status = :status");
        $stmt->setParameter("status", $status);

        return $stmt->getQuery()->getSingleScalarResult();
    }
    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByUser($user) {
        $stmt = $this->createQueryBuilder('o');
        $stmt->where("o.user = :user");
        $stmt->setParameter("user", $user);

        return $stmt->getQuery()->getResult();
    }
}

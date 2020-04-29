<?php

namespace App\Repository;

use App\Entity\State;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method State|null find($id, $lockMode = null, $lockVersion = null)
 * @method State|null findOneBy(array $criteria, array $orderBy = null)
 * @method State[]    findAll()
 * @method State[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, State::class);
    }

    // /**
    //  * @return State[] Returns an array of State objects
    //  */

    public function findByLowStock ( int $lowStockLimit, string $state ) {

        $stmt = $this->createQueryBuilder('s');
        $stmt->select();
        $stmt->where("stock < :lowStockLimit");
        $stmt->andWhere("state = :state");
        $stmt->setParameter("lowStockLimit", $lowStockLimit);
        $stmt->setParameter('state', $state);

        return $stmt->getQuery()->getScalarResult();
    }

    public function countByLowStockAndNew ( int $lowStockLimit, string $state ) {
        $stmt = $this->createQueryBuilder('s');
        $stmt->select("count(s.id)");
        $stmt->where("s.stock < :lowStockLimit");
        $stmt->andWhere("s.state = :state");
        $stmt->setParameter("lowStockLimit", $lowStockLimit);
        $stmt->setParameter('state', $state);

        return $stmt->getQuery()->getSingleScalarResult();
    }

    public function countByNewUnavailable (int $unavailable, string $state) {
        $stmt = $this->createQueryBuilder('s');
        $stmt->select("count(s.id)");
        $stmt->where("s.stock = :unavailable");
        $stmt->andWhere("s.state = :state");
        $stmt->setParameter("unavailable", $unavailable);
        $stmt->setParameter('state', $state);

        return $stmt->getQuery()->getSingleScalarResult();
    }
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
    public function findOneBySomeField($value): ?State
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

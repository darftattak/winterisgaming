<?php

namespace App\Repository;

use App\Entity\Product;
use App\Model\SearchData;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }


    public function searchByName( $query ) {
        $stmt = $this->createQueryBuilder('e');
        $stmt->where( 'e.name LIKE :query' );
        $stmt->setParameter('query', '%' . $query . '%');

        return $stmt->getQuery()->getResult();
    }

    public function getRandom() {
        $stmt = $this->createQueryBuilder('e');
        
        //TODO
        // Installer https://github.com/beberlei/DoctrineExtensions
        // Ajouter une séléction aléatoire
        $stmt->orderBy('RAND()');
        $stmt->setMaxResults(8);

        return $stmt->getQuery()->getResult();
    }

    public function countByHasNoPhoto() {
        $stmt = $this->createQueryBuilder('p');
        $stmt->select("count(p.id)");
        $stmt->where("p.photos IS EMPTY");

        return $stmt->getQuery()->getSingleScalarResult();
    }

    public function countByHasNoState() {
        $stmt = $this->createQueryBuilder('p');
        $stmt->select("count(p.id)");
        $stmt->where("p.states IS EMPTY");

        return $stmt->getQuery()->getSingleScalarResult();
    }

    public function findSearch(SearchData $search)
    {

        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.categories', 'c');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('p.name LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->min)) {
            $query = $query
                ->andWhere('p.price >= :min')
                ->setParameter('min', $search->min);
        }

        if (!empty($search->max)) {
            $query = $query
                ->andWhere('p.price <= :max')
                ->setParameter('max', $search->max);
        }

        

        if (!empty($search->categories)) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }

        return $query->getQuery()->getResult();
    }

    private function getSearchQuery(SearchData $search, $ignorePrice = false)/* : QueryBuilder */
    {
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

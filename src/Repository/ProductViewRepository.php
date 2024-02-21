<?php

namespace App\Repository;

use App\Entity\ProductView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductView>
 *
 * @method ProductView|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductView|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductView[]    findAll()
 * @method ProductView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductView::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ProductView $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ProductView $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function orderByProductMostViews() {
        return $this->createQueryBuilder('v')
            ->select('identity(v.product) product, COUNT(v.product) as viewCount')
            ->leftJoin('v.product', 'p')
            ->andWhere('p.statement = :statement')
            ->setParameter('statement', 'Disponible')
            ->groupBy('v.product')
            ->orderBy('viewCount', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return ProductView[] Returns an array of ProductView objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProductView
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

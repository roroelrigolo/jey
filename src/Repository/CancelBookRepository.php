<?php

namespace App\Repository;

use App\Entity\CancelBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CancelBook>
 *
 * @method CancelBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method CancelBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method CancelBook[]    findAll()
 * @method CancelBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CancelBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CancelBook::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CancelBook $entity, bool $flush = true): void
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
    public function remove(CancelBook $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

//    /**
//     * @return CancelBook[] Returns an array of CancelBook objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CancelBook
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

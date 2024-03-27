<?php

namespace App\Repository;

use App\Entity\MessageStep;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MessageStep>
 *
 * @method MessageStep|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageStep|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageStep[]    findAll()
 * @method MessageStep[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageStepRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageStep::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(MessageStep $entity, bool $flush = true): void
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
    public function remove(MessageStep $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

//    /**
//     * @return MessageStep[] Returns an array of MessageStep objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MessageStep
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

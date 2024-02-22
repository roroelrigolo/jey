<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Player>
 *
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Player $entity, bool $flush = true): void
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
    public function remove(Player $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findByQuery(string $query): array
    {
        if (empty($query)) {
            return $this->createQueryBuilder('p')
                ->orderBy('p.lastName', 'ASC')
                ->getQuery()
                ->getResult()
                ;
        }
        return $this->createQueryBuilder('p')
            ->andWhere('p.lastName LIKE :query or p.firstName LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->orderBy('p.lastName', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findBySport($id_sport): array {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.teams', 't')
            ->leftJoin('t.league', 'l')
            ->leftJoin('l.sport', 's')
            ->where('s.id = :id_sport')
            ->orderBy('p.lastName', 'ASC')
            ->setParameter('id_sport', $id_sport)
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return Player[] Returns an array of Player objects
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

//    public function findOneBySomeField($value): ?Player
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

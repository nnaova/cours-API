<?php

namespace App\Repository;

use App\Entity\UserApp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserApp>
 *
 * @method UserApp|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserApp|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserApp[]    findAll()
 * @method UserApp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAppRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserApp::class);
    }

//    /**
//     * @return UserApp[] Returns an array of UserApp objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserApp
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

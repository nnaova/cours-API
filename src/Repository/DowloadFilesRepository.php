<?php

namespace App\Repository;

use App\Entity\DowloadFiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DowloadFiles>
 *
 * @method DowloadFiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method DowloadFiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method DowloadFiles[]    findAll()
 * @method DowloadFiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DowloadFilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DowloadFiles::class);
    }

//    /**
//     * @return DowloadFiles[] Returns an array of DowloadFiles objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DowloadFiles
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

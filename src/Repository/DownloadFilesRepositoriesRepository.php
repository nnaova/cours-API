<?php

namespace App\Repository;

use App\Entity\DownloadFilesRepositories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DownloadFilesRepositories>
 *
 * @method DownloadFilesRepositories|null find($id, $lockMode = null, $lockVersion = null)
 * @method DownloadFilesRepositories|null findOneBy(array $criteria, array $orderBy = null)
 * @method DownloadFilesRepositories[]    findAll()
 * @method DownloadFilesRepositories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DownloadFilesRepositoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DownloadFilesRepositories::class);
    }

//    /**
//     * @return DownloadFilesRepositories[] Returns an array of DownloadFilesRepositories objects
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

//    public function findOneBySomeField($value): ?DownloadFilesRepositories
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

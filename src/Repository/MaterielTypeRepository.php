<?php

namespace App\Repository;

use App\Entity\MaterielType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MaterielType>
 *
 * @method MaterielType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterielType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterielType[]    findAll()
 * @method MaterielType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterielTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterielType::class);
    }

//    /**
//     * @return MaterielType[] Returns an array of MaterielType objects
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

//    public function findOneBySomeField($value): ?MaterielType
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

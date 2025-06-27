<?php

namespace App\Repository;

use App\Entity\BugReport;
use App\Traits\FindOfFailTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BugReport>
 */
class BugReportRepository extends ServiceEntityRepository
{
    use FindOfFailTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BugReport::class);
    }

    public function getUnresolvedDuplicates()
    {
        $qb = $this->createQueryBuilder('b');
        return $qb->select('b')
                ->Where($qb->expr()->isNotNull('b.is_duplicate_of'))
                ->andWhere($qb->expr()->isNull('b.task'));
    }

    public function getUnresolvedReports()
    {
        $qb = $this->createQueryBuilder('b');
        return $qb->select('b')
            ->Where($qb->expr()->isNull('b.is_duplicate_of'))
            ->andWhere($qb->expr()->isNull('b.task'));
    }


    public function getFilteredByTask($task){

        $qb = $this->createQueryBuilder('b');

        return $qb->select('b')->Where('b.task = :task')->setParameter('task', $task);
    }
    //    /**
    //     * @return BugReport[] Returns an array of BugReport objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BugReport
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

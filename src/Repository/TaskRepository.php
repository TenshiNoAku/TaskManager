<?php

namespace App\Repository;

use App\Entity\Task;
use App\Traits\FilterableTrait;
use App\Traits\FindOfFailTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    use FindOfFailTrait;
    use FilterableTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }







    public function findWithPagination(int $page, int $perPage , array $orders, array $filter): Paginator {

        $querybuilder = $this->createQueryBuilder('task')
            ->join('task.project', 'project')
            ->join('task.status', 'status')
            ->join('task.priority', 'priority')
            ->join('task.tracker', 'tracker')
            ->setMaxResults($perPage)
            ->setFirstResult($perPage*($page-1));
        $querybuilder = $this->addOrdering($querybuilder, $orders);
        $querybuilder = $this->addFiltering($querybuilder, $filter);
        $query = $querybuilder->getQuery();
        $paginator = new Paginator($query);
        return $paginator;
    }

//    /**
//     * @return Task[] Returns an array of Task objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Task
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

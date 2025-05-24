<?php

namespace App\Repository;

use App\Entity\Project;
use App\Services\ServiceException;
use App\Traits\FilterableTrait;
use App\Traits\FindOfFailTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Orm\Query;
use Doctrine\Common\Collections\Criteria;
/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    use FindOfFailTrait;
    use FilterableTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }


  public function findWithPagination(int $page, int $perPage , array $orders, array $filter): Paginator {

        $querybuilder = $this->createQueryBuilder('p')
            ->setMaxResults($perPage)
            ->setFirstResult($perPage*($page-1));
        $querybuilder = $this->addOrdering($querybuilder, $orders);
        $querybuilder = $this->addFiltering($querybuilder, $filter);

        $query = $querybuilder->getQuery();
        $paginator = new Paginator($query);
        return $paginator;
    }


    //    /**
    //     * @return Project[] Returns an array of Project objects
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

    //    public function findOneBySomeField($value): ?Project
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

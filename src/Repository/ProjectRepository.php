<?php

namespace App\Repository;

use App\Entity\Project;
use App\Services\ServiceException;
use App\Traits\FindOfFailTrait;
use App\Traits\HandleIdInURLTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    use FindOfFailTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }


//TODO убрать итератор


//    public function findWithPagination(int $page) {
//        $dql = "SELECT p FROM App\Entity\Project p";
//        $query = $this->getEntityManager()->createQuery($dql)->setMaxResults(2)->setFirstResult(2*($page-1))->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);
//        $paginator = new Paginator($query, $fetchJoinCollection = true);
//        return iterator_to_array($paginator->getIterator());
//    }

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

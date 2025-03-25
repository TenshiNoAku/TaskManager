<?php

namespace App\Repository;

use App\Entity\Project;
use App\Services\ServiceException;
use App\Traits\HandleIdInURLTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    use HandleIdInURLTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findOrFail($id) : Project
    {
        $errors = $this->handleId($id);
        if (!empty($errors)) {
            throw new ServiceException(400,json_encode($errors),null,['Content-Type: application/json']);
        }

        $project = $this->find($id);

        if(!$project){
            throw new ServiceException(404,json_encode([
                    "error" =>
                        ["code" => 404,
                            "message" => "Resource not found",
                            "details" => "The requested resource with ID '$id' could not be found.",
                        ]],JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                ,null,['Content-Type: application/json']);
        }

        return $project;
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

<?php

namespace App\Services;

use App\DTO\Requests\Create\ProjectCreateRequest;
use App\DTO\Requests\Update\ProjectUpdateRequest;
use App\Entity\Project;
use App\Traits\EntityNotFoundTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;


class ProjectService
{
    use EntityNotFoundTrait;
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private RequestStack $requestStack;

    private ValidatorInterface $validator;


    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, RequestStack $requestStack, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->requestStack = $requestStack;
        $this->validator = $validator;
    }


    public function delete($id) : Project
    {
        $project = $this->entityManager->getRepository(Project::class)->findOrFail($id);
        $this->entityManager->remove($project);
        $this->entityManager->flush();
        return $project;
    }

    public function create(ProjectCreateRequest $projectCreateRequest) : Project{
        $project = new Project();
        $project->setName($projectCreateRequest->name);
        $this->entityManager->persist($project);
        $this->entityManager->flush();
        return $project;
    }

    public function update(ProjectUpdateRequest $projectUpdateRequest,$id) : Project{
        $project =  $this->entityManager->getRepository(Project::class)->findOrFail($id);
        $project->setName($projectUpdateRequest->name);
        $this->entityManager->persist($project);
        $this->entityManager->flush();
        return $project;
    }


}
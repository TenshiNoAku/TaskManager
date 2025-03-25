<?php

namespace App\Controller;


use App\DTO\Create\ProjectCreateDTO;
use App\DTO\Update\ProjectUpdateDTO;
use App\Entity\Task;
use App\Services\ProjectService;
use App\Traits\HandleIdInURLTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use App\Entity\Project;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/v1/projects')]
final class ProjectController extends AbstractController
{
    use HandleIdInURLTrait;

    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;

    public function __construct(SerializerInterface $serializer , EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }


    #[Route('/', name: 'project_index', methods: ['GET'],)]

    public function index(ProjectService $projectService) : Response
    {
        $projects = new ArrayCollection($this->entityManager->getRepository(Project::class)->findAll());
        return new JsonResponse(
            $projects->map(function (Project $project) {
                return [
                    'id' => $project->getId(),
                    'name' => $project->getName()
                ];
            })->toArray()
        );
    }

    #[Route('/{id}/tasks/', name: 'project_tasks', methods: ['GET'])]

    public function getTasks(ProjectService $projectService, $id)
    {

        $project = $projectService->getById($id);
        return new JsonResponse([
            'id' => $project->getId(),
            'name' => $project->getName(),
            'tasks' => $project->getTasks()->map(function (Task $task) {
                return [
                    'id' => $task->getId(),
                    'name' => $task->getName(),
                    'priority' => $task->getPriority()->getName(),
                    'status' => $task->getStatus()->getName(),
                    'description' => $task->getDescription(),
                ];
            })->toArray(),

        ]);

    }

    #[Route('/{id}/', name: 'project_show', methods: ['GET'])]

    public function show(ProjectService $projectService, $id): JsonResponse
    {
        $project = $projectService->getById($id);
        return new JsonResponse([
            'id' => $project->getId(),
            'name' => $project->getName(),
        ]);
    }

    #[Route('/', name: 'project_create', methods: ['POST'])]

    public function create(ProjectService $projectService,
    #[MapRequestPayload] ProjectCreateDTO $projectCreateDTO) {

        $project = new Project();
        $project->setName($projectCreateDTO->name);
        $this->entityManager->persist($project);
        $this->entityManager->flush();
        return new JsonResponse(['id'=> $project->getId(),'name'=>$project->getName()]);
    }

    #[Route('/{id}/', name: 'project_create', methods: ['PUT','PATCH'])]

    public function update(ProjectService $projectService,
                           #[MapRequestPayload] ProjectUpdateDTO $projectCreateDTO, $id) {

        $project =  $projectService->getById($id);
        $project->setName($projectCreateDTO->name);
        $this->entityManager->persist($project);
        $this->entityManager->flush();
        return new JsonResponse(['id'=> $project->getId(),'name'=>$project->getName()]);
    }



}

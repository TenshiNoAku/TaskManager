<?php

namespace App\Controller;


use App\DTO\Entity\ProjectEntityDTO;
use App\DTO\Entity\TasksEntityDTO;
use App\DTO\Requests\Create\ProjectCreateRequest;
use App\DTO\Requests\JsonApiResponse;
use App\DTO\Requests\Update\ProjectUpdateRequest;
use App\Entity\Project;
use App\Entity\Task;
use App\Services\ProjectService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/v1/projects')]
final class ProjectController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/', name: 'project_index', methods: ['GET'],)]

    public function index(ProjectService $projectService) : Response
    {
        $projects = new ArrayCollection($this->entityManager->getRepository(Project::class)->findAll());

        $projectDTOs = $projects->map(function (Project $project) {
                $projectDTO = new ProjectEntityDTO($project);
                return $projectDTO->toArray();
            })->toArray();
        return new JsonApiResponse($projectDTOs);

    }

    #[Route('/{id}/tasks/', name: 'project_tasks', methods: ['GET'])]

    public function getTasks($id) : JsonResponse
    {

        $project = $this->entityManager->getRepository(Project::class)->findOrFail($id);

        $tasks = $project->getTasks()->map(function (Task $task) {
            $taskDTO = new TasksEntityDTO($task);
            return $taskDTO->toArray();
        })->toArray();

        return new JsonApiResponse([
            $tasks
        ]);

    }

    #[Route('/{id}/', name: 'project_show', methods: ['GET'])]

    public function show($id): JsonResponse
    {
        $project = $this->entityManager->getRepository(Project::class)->findOrFail($id);
        $projectDTO = new ProjectEntityDTO($project);
        return new JsonApiResponse($projectDTO->toArray());
    }

    #[Route('/', name: 'project_create', methods: ['POST'])]

    public function create(ProjectService $projectService,
    #[MapRequestPayload] ProjectCreateRequest $projectCreateRequest): JsonResponse {

        $project = new Project();
        $project->setName($projectCreateRequest->name);
        $this->entityManager->persist($project);
        $this->entityManager->flush();
        $projectDTO = new ProjectEntityDTO($project);
        return new JsonApiResponse($projectDTO->toArray());
    }

    #[Route('/{id}/', name: 'project_update', methods: ['PUT','PATCH'])]

    public function update(ProjectService $projectService,
                           #[MapRequestPayload] ProjectUpdateRequest $projectUpdateRequest,
                           $id) : JsonResponse{

        $project =  $projectService->getById($id);
        $project->setName($projectUpdateRequest->name);
        $this->entityManager->persist($project);
        $this->entityManager->flush();
        $projectDTO = new ProjectEntityDTO($project);

        return new JsonApiResponse($projectDTO->toArray());
    }


    #[Route('/{id}/', name: 'project_delete', methods: ['DELETE'])]
    public function delete($id) : JsonResponse{
        $project = $this->entityManager->getRepository(Project::class)->findOrFail($id);
        $projectDTO = new ProjectEntityDTO($project);

        $this->entityManager->remove($project);
        $this->entityManager->flush();
        return new JsonApiResponse($projectDTO->toArray());
    }


}

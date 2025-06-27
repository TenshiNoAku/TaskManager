<?php

namespace App\Controller;

use App\DTO\Entity\ProjectEntityDTO;
use App\DTO\Entity\TasksEntityDTO;
use App\DTO\PaginatorMetaDTO;
use App\DTO\Requests\Create\TaskCreateRequest;
use App\DTO\Requests\JsonApiResponse;
use App\DTO\Requests\Update\TaskUpdateRequest;
use App\Entity\Priority;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Task;
use App\Entity\Tracker;
use App\Services\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Common\Collections\ArrayCollection;

#[Route('/api/v1/tasks')]
class TaskController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'task' ,methods: ['GET'])]
    public function index(#[MapQueryParameter] int $page=1, #[MapQueryParameter] int $per_page=100, #[MapQueryParameter] array $order = array() ,  #[MapQueryParameter] array $filter = array()): JsonResponse
    {
        $paginator = $this->entityManager->getRepository(Task::class)->findWithPagination($page,$per_page,$order,$filter);
        $data =new  ArrayCollection(iterator_to_array($paginator->getIterator()));
        $data = $data->map(function (Task $task) {
            $taskDTO = new TasksEntityDTO($task);
            return $taskDTO->toArray();
        })->toArray();
        $meta = new PaginatorMetaDTO($paginator);
        return new JsonApiResponse(data:$data,meta:$meta->toArray());
    }

    #[Route('/{id}/', name: 'task_show', methods: ['GET'])]
    public function show($id): JsonResponse
    {
        $task = $this->entityManager->getRepository(Task::class)->findOrFail($id);
        $taskDTO = new TasksEntityDTO($task);
        return new JsonApiResponse($taskDTO->toArray());
    }

    #[Route('/{id}/', name: 'task_delete' , methods: ['DELETE'])]
    public function delete(TaskService $service,$id): JsonResponse
    {
        $task = $service->delete($id);
        $taskDTO = new TasksEntityDTO($task);
        return new JsonApiResponse($taskDTO->toArray());
    }

    #[Route('/', name: 'task_create', methods: ['POST'])]

    public function create(#[MapRequestPayload] TaskCreateRequest $request, TaskService $service): JsonResponse
    {
        $task = $service->create($request);
        $taskDTO = new TasksEntityDTO($task);
        return new JsonApiResponse($taskDTO->toArray());

    }

    #[Route('/{id}/', name: 'task_update', methods: ['PUT','PATCH'])]

    public function update(#[MapRequestPayload] TaskUpdateRequest $request,TaskService $service,$id): JsonResponse
    {
        $task = $service->update($request, $id);
        $taskDTO = new TasksEntityDTO($task);
        return new JsonApiResponse($taskDTO->toArray());
    }


    #[Route('/{id}/test/',name: 'task_test', methods: ['GET'])]
    public function test($id)
    {
        $task = $this->entityManager->getRepository(Task::class)->findOrFail($id);
        $task->calculatePriority($this->entityManager);
        $taskDTO = new TasksEntityDTO($task);
        return new JsonApiResponse($taskDTO->toArray());
    }
}
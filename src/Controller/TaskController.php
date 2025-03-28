<?php

namespace App\Controller;

use App\DTO\Entity\TasksEntityDTO;
use App\DTO\Requests\Create\TaskCreateRequest;
use App\DTO\Requests\JsonApiResponse;
use App\DTO\Requests\Update\TaskUpdateRequest;
use App\Entity\Priority;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Task;
use App\Entity\Tracker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function index(): JsonResponse
    {
        $tasks = new ArrayCollection($this->entityManager->getRepository(Task::class)->findAll());
        $taskDTOs = $tasks->map(function (Task $task) {
            $taskDTO = new TasksEntityDTO($task);
            return $taskDTO->toArray();
        })->toArray();
        return new JsonApiResponse($taskDTOs);
    }

    #[Route('/{id}/', name: 'task_show', methods: ['GET'])]
    public function show($id): JsonResponse
    {
        $task = $this->entityManager->getRepository(Task::class)->findOrFail($id);
        $taskDTO = new TasksEntityDTO($task);
        return new JsonApiResponse($taskDTO->toArray());
    }

    #[Route('/{id}/', name: 'task_delete' , methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        $task = $this->entityManager->getRepository(Task::class)->findOrFail($id);
        $taskDTO = new TasksEntityDTO($task);
        $this->entityManager->remove($task);
        $this->entityManager->flush();
        return new JsonApiResponse($taskDTO->toArray());
    }

    #[Route('/', name: 'task_create', methods: ['POST'])]

    public function create(#[MapRequestPayload] TaskCreateRequest $request): JsonResponse
    {
        $task = new Task();

        $task->setName($request->name);
        $task->setDescription($request->description);
        $task->setPriority($this->entityManager->getRepository(Priority::class)->findOrFail($request->priority_id));
        $task->setProject($this->entityManager->getRepository(Project::class)->findOrFail($request->project_id));
        $task->setStatus($this->entityManager->getRepository(Status::class)->findOrFail($request->status_id));
        $task->setTracker($this->entityManager->getRepository(Tracker::class)->findOrFail($request->tracker_id));
        $task->setDeadline(new \DateTime($request->deadline));
        $task->setFromDate(new \DateTime($request->from_date));
        $task->setTimeCost(new \DateTime($request->time_cost));
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        $taskDTO = new TasksEntityDTO($task);
        return new JsonApiResponse($taskDTO->toArray());

    }

    #[Route('/{id}/', name: 'task_update', methods: ['PUT','PATCH'])]

    public function update(#[MapRequestPayload] TaskUpdateRequest $request, $id): JsonResponse
    {
        $task = $this->entityManager->getRepository(Task::class)->findOrFail($id);

        $task->setName($request->name??$task->getName());
        $task->setDescription($request->description??$task->getDescription());
        $task->setPriority($this->entityManager->getRepository(Priority::class)->findOrFail($request->priority_id??$task->getPriority()->getId()));
        $task->setProject($this->entityManager->getRepository(Project::class)->findOrFail($request->project_id??$task->getProject()->getId()));
        $task->setStatus($this->entityManager->getRepository(Status::class)->findOrFail($request->status_id??$task->getStatus()->getId()));
        $task->setTracker($this->entityManager->getRepository(Tracker::class)->findOrFail($request->tracker_id??$task->getTracker()->getId()));
        $task->setDeadline(new \DateTime($request->deadline??$task->getDeadline()->format('d-m-Y')));
        $task->setFromDate(new \DateTime($request->from_date??$task->getFromDate()));
        $task->setTimeCost(new \DateTime($request->time_cost??$task->getTimeCost()));
        $this->entityManager->flush();
        $taskDTO = new TasksEntityDTO($task);
        return new JsonApiResponse($taskDTO->toArray());

    }


}
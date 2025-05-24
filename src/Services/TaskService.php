<?php

namespace App\Services;

use App\DTO\Entity\TasksEntityDTO;
use App\DTO\Requests\Create\TaskCreateRequest;
use App\DTO\Requests\Update\TaskUpdateRequest;
use App\Entity\Priority;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Task;
use App\Entity\Tracker;
use App\Traits\EntityNotFoundTrait;
use Composer\Semver\Constraint\Constraint;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints as Assert;

class TaskService
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

    public function delete($id) : Task
    {
        $task = $this->entityManager->getRepository(Task::class)->findOrFail($id);
        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return $task;
    }

    public function update(#[MapRequestPayload] TaskUpdateRequest $request, $id) : Task
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

        return $task;
    }

    public function create(TaskCreateRequest $request) : Task
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
        return $task;
    }



}
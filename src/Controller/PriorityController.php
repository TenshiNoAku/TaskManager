<?php

namespace App\Controller;

use App\DTO\Entity\PriorityEntityDTO;
use App\DTO\Requests\Create\PriorityCreateRequest;
use App\DTO\Requests\JsonApiResponse;
use App\DTO\Requests\Update\PriorityUpdateRequest;
use App\Entity\Priority;
use App\Services\PriorityService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/priorities')]
class PriorityController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'priority_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $priorities = new ArrayCollection($this->entityManager->getRepository(Priority::class)->findAll());
        $prioritiesDTO = $priorities->map(function (Priority $priority) {
            $priorityDTO = new PriorityEntityDTO($priority);
            return $priorityDTO->toArray();
        });
        return new JsonApiResponse($prioritiesDTO->toArray());
    }

    #[Route('/{id}/', name: 'priority_show', methods: ['GET'])]
    public function show($id): JsonResponse
    {
        $priority = $this->entityManager->getRepository(Priority::class)->findOrFail($id);
        $priorityDTO = new PriorityEntityDTO($priority);

        return new JsonApiResponse($priorityDTO->toArray());

    }

    #[Route('/{id}/', name: 'priority_update', methods: ['PUT'])]
    public function update(#[MapRequestPayload] PriorityUpdateRequest $request, PriorityService $service,$id): JsonResponse
    {
        $priority = $service->update($request,$id);
        $priorityDTO = new PriorityEntityDTO($priority);
        return new JsonApiResponse($priorityDTO->toArray());

    }

    #[Route('/', name: 'priority_create', methods: ['POST'])]
    public function create(#[MapRequestPayload] PriorityCreateRequest $request, PriorityService $service): JsonResponse
    {
        $priority = $service->create($request);
        $priorityDTO = new PriorityEntityDTO($priority);
        return new JsonApiResponse($priorityDTO->toArray());
    }


    #[Route('/{id}/', name: 'priority_delete', methods: ['DELETE'])]
    public function delete($id, PriorityService $service): JsonResponse
    {
        $priority = $service->delete($id);
        $priorityDTO = new PriorityEntityDTO($priority);
        return new JsonApiResponse($priorityDTO->toArray());

    }

}
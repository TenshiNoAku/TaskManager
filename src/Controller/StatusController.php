<?php

namespace App\Controller;

use App\DTO\Entity\StatusEntityDTO;
use App\DTO\Requests\Create\StatusCreateRequest;
use App\DTO\Requests\JsonApiResponse;
use App\DTO\Requests\Update\StatusUpdateRequest;
use App\Entity\Status;
use App\Services\StatusService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;


#[Route('/api/v1/statuses')]
class StatusController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'status_controller', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $statuses = new ArrayCollection($this->entityManager->getRepository(Status::class)->findAll());
        $statusesDTO = $statuses->map(function (Status $status) {
            $statusDTO = new StatusEntityDTO($status);
            return $statusDTO->toArray();
        });
        return new JsonApiResponse($statusesDTO->toArray());
    }

    #[Route('/{id}/', name: 'status_get', methods: ['GET'])]
    public function show($id): JsonResponse
    {
        $status = $this->entityManager->getRepository(Status::class)->findOrFail($id);
        $statusDTO = new StatusEntityDTO($status);
        return new JsonApiResponse($statusDTO->toArray());

    }

    #[Route('/{id}/', name: 'status_update', methods: ['PUT'])]
    public function update(#[MapRequestPayload] StatusUpdateRequest $request,StatusService $service ,$id): JsonResponse
    {
        $status = $service->update($id,$request);
        $statusDTO = new StatusEntityDTO($status);
        return new JsonApiResponse($statusDTO->toArray());

    }

    #[Route('/', name: 'status_create', methods: ['POST'])]
    public function create(#[MapRequestPayload] StatusCreateRequest $request, StatusService $service): JsonResponse
    {
        $status = $service->create($request);
        $statusDTO = new StatusEntityDTO($status);
        return new JsonApiResponse($statusDTO->toArray());
    }

    #[Route('/{id}', name: 'status_delete', methods: ['DELETE'])]
    public function delete(StatusService $service, $id): JsonResponse
    {
        $status = $service->delete($id);
        $statusDTO = new StatusEntityDTO($status);
        return new JsonApiResponse($statusDTO->toArray());
    }

}
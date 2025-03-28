<?php

namespace App\Controller;

use App\DTO\Entity\TrackerEntityDTO;
use App\DTO\Requests\Create\TrackerCreateRequest;
use App\DTO\Requests\JsonApiResponse;
use App\DTO\Requests\Update\TrackerUpdateRequest;
use App\Entity\Tracker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Common\Collections\ArrayCollection;

#[Route('/api/v1/trackers')]
class TrackerController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'tracker_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $trackers = new ArrayCollection($this->entityManager->getRepository(Tracker::class)->findAll());
        return new JsonApiResponse($trackers->map(function (Tracker $tracker) {
            $trackerDTO = new TrackerEntityDTO($tracker);
            return $trackerDTO->toArray();
        })->toArray());
    }

    #[Route('/{id}/', name: 'tracker_show', methods: ['GET'])]
    public function show($id): JsonResponse
    {
        $tracker = $this->entityManager->getRepository(Tracker::class)->findOrFail($id);
        $trackerDTO = new TrackerEntityDTO($tracker);
        return new JsonApiResponse($trackerDTO->toArray());
    }

    #[Route('/', name: 'tracker_create', methods: ['POST'])]
    public function create(#[MapRequestPayload] TrackerCreateRequest $request): JsonResponse
    {
        $tracker = new Tracker();
        $tracker->setName($request->name);
        $this->entityManager->persist($tracker);
        $this->entityManager->flush();

        $trackerDTO = new TrackerEntityDTO($tracker);
        return new JsonApiResponse($trackerDTO->toArray());
    }

    #[Route('/{id}/', name: 'tracker_update', methods: ['PUT'])]
    public function update(#[MapRequestPayload] TrackerUpdateRequest $request, $id): JsonResponse
    {
        $tracker = $this->entityManager->getRepository(Tracker::class)->findOrFail($id);
        $tracker->setName($request->name);
        $this->entityManager->persist($tracker);
        $this->entityManager->flush();
        $trackerDTO = new TrackerEntityDTO($tracker);
        return new JsonApiResponse($trackerDTO->toArray());
    }

    #[Route('/{id}/', name: 'tracker_delete', methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        $tracker = $this->entityManager->getRepository(Tracker::class)->findOrFail($id);
        $trackerDTO = new TrackerEntityDTO($tracker);
        $this->entityManager->remove($tracker);
        $this->entityManager->flush();
        return new JsonApiResponse($trackerDTO->toArray());
    }


}
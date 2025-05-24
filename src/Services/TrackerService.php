<?php

namespace App\Services;

use App\DTO\Requests\Create\TrackerCreateRequest;
use App\DTO\Requests\Update\TrackerUpdateRequest;
use App\Entity\Tracker;
use Doctrine\ORM\EntityManagerInterface;

class TrackerService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function delete($id) : Tracker
    {
        $tracker = $this->entityManager->getRepository(Tracker::class)->findOrFail($id);
        $this->entityManager->remove($tracker);
        $this->entityManager->flush();

        return $tracker;
    }

    public function update(TrackerUpdateRequest $request, $id): Tracker{
        $tracker = $this->entityManager->getRepository(Tracker::class)->findOrFail($id);
        $tracker->setName($request->name);
        $this->entityManager->persist($tracker);
        $this->entityManager->flush();

        return $tracker;
    }

    public function create(TrackerCreateRequest $request) : Tracker{
        $tracker = new Tracker();
        $tracker->setName($request->name);
        $this->entityManager->persist($tracker);
        $this->entityManager->flush();

        return $tracker;

    }
}
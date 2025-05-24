<?php

namespace App\Services;

use App\DTO\Entity\PriorityEntityDTO;
use App\DTO\Requests\Create\PriorityCreateRequest;
use App\DTO\Requests\Update\PriorityUpdateRequest;
use App\Entity\Priority;
use Doctrine\ORM\EntityManagerInterface;

class PriorityService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function delete($id) : Priority
    {
        $priority = $this->entityManager->getRepository(Priority::class)->findOrFail($id);
        $this->entityManager->remove($priority);
        $this->entityManager->flush();
        return $priority;
    }

    public function update( PriorityUpdateRequest $request, $id) : Priority{
        $priority = $this->entityManager->getRepository(Priority::class)->findOrFail($id);
        $priority->setName($request->name);

        $this->entityManager->persist($priority);
        $this->entityManager->flush();

        return $priority;

    }

    public function create(PriorityCreateRequest $request): Priority{
        $priority = new Priority();
        $priority->setName($request->name);
        $this->entityManager->persist($priority);
        $this->entityManager->flush();
        return $priority;
    }

}
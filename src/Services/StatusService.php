<?php

namespace App\Services;


use App\DTO\Entity\StatusEntityDTO;
use App\DTO\Requests\Create\StatusCreateRequest;
use App\DTO\Requests\Update\StatusUpdateRequest;
use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class StatusService
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function delete($id) : Status
    {
        $status = $this->entityManager->getRepository(Status::class)->findOrFail($id);
        $this->entityManager->remove($status);
        $this->entityManager->flush();
        return $status;
    }

    public function update(StatusUpdateRequest $request, $id) : Status
    {
        $status = $this->entityManager->getRepository(Status::class)->findOrFail($id);
        $status->setName($request->name);
        $this->entityManager->persist($status);
        $this->entityManager->flush();
        return $status;
    }

    public function create(StatusCreateRequest $request): Status{
        $status = new Status();
        $status->setName($request->name);
        $this->entityManager->persist($status);
        $this->entityManager->flush();

        return $status;
    }

}
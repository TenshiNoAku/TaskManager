<?php

namespace App\Controller;

use App\DTO\Entity\BugReportEntityDTO;
use App\DTO\Requests\Create\BugReportCreateRequest;
use App\DTO\Requests\JsonApiResponse;
use App\DTO\Requests\Update\BugReportUpdateRequest;
use App\DTO\Requests\Update\PriorityUpdateRequest;
use App\Entity\BugReport;
use App\Services\BugReportService;
use App\Entity\Task;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;


#[Route('/api/v1/bug_report')]

class BugReportController  extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'bug_report_index', methods: ['GET'])]

    public function index(): JsonResponse
    {
        $bug_reports = new ArrayCollection($this->entityManager->getRepository(BugReport::class)->findAll());
        $bug_reportsDTO = $bug_reports->map(function (BugReport $bug_report) {
            $bug_reportDTO = new BugReportEntityDTO($bug_report);
            return $bug_reportDTO->toArray();
        });
        return new JsonApiResponse($bug_reportsDTO->toArray());
    }


    #[Route('/{id}/', name: 'bug_report_show', methods: ['GET'])]

    public function show($id): JsonResponse
    {
        $bug_report = $this->entityManager->getRepository(BugReport::class)->findOrFail($id);
        $bug_reportDTO = new BugReportEntityDTO($bug_report);
        return new JsonApiResponse($bug_reportDTO->toArray());
    }



    #[Route('/{id}/', name: 'bug_report_delete', methods: ['DELETE'])]
    public function delete(BugReportService $service, $id): JsonResponse
    {
        $bug_report = $service->delete($id);
        $bug_reportDTO = new BugReportEntityDTO($bug_report);
        return new JsonApiResponse($bug_reportDTO->toArray());
    }


    #[Route('/', name: 'bug_report_create', methods: ['POST'])]
    public function create(#[MapRequestPayload] BugReportCreateRequest $request,BugReportService $service): JsonResponse
    {
        $bug_report = $service->create($request);
        $bug_reportDTO = new BugReportEntityDTO($bug_report);
        return new JsonApiResponse($bug_reportDTO->toArray());
    }


    #[Route('/{id}/', name: 'bug_report_update', methods: ['PUT', 'PATCH'])]
    public function update(#[MapRequestPayload] BugReportUpdateRequest $request,BugReportService $service, $id): JsonResponse
    {
        $bug_report = $service->update(request: $request,id: $id);
        $bug_reportDTO = new BugReportEntityDTO($bug_report);
        return new JsonApiResponse($bug_reportDTO->toArray());
    }

    #[Route('/{id}/approve/', name: 'bug_report_approve', methods: ['GET'])]
    public function approveDuplicate(BugReportService $service,$id): JsonResponse
    {
        $bug_report = $service->approveDuplicate($id);
        $bug_reportDTO = new BugReportEntityDTO($bug_report);
        return new JsonApiResponse($bug_reportDTO->toArray());
    }

    #[Route('/{id}/decline/', name: 'bug_report_decline', methods: ['GET'])]

    public function declineDuplicate(BugReportService $service,$id): JsonResponse
    {
        $bug_report = $service->declineDuplicate($id);
        $bug_reportDTO = new BugReportEntityDTO($bug_report);
        return new JsonApiResponse($bug_reportDTO->toArray());
    }
}
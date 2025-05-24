<?php

namespace App\Services;

use App\DTO\Requests\Create\BugReportCreateRequest;
use App\DTO\Requests\Update\BugReportUpdateRequest;
use App\Entity\BugReport;
use App\Entity\Task;
use App\Repository\BugReportRepository;
use Doctrine\ORM\EntityManagerInterface;

class BugReportService
{
    public function __construct(private BugReportRepository $bugReportRepository, private EntityManagerInterface $entityManager)
    {

    }

    public function delete($id): BugReport
    {
        $bug_report = $this->entityManager->getRepository(BugReport::class)->findOrFail($id);
        $this->entityManager->remove($bug_report);
        $this->entityManager->flush();
        return $bug_report;
    }

    public function update(BugReportUpdateRequest $request, $id): BugReport
    {
        $bug_report = $this->bugReportRepository->findOrFail($id);
        $bug_report->setName($request->name ?? $bug_report->getName());
        $bug_report->setText($request->text ?? $bug_report->getText());
        if (isset($request->task)) {
            $bug_report->setTask($this->entityManager->getRepository(Task::class)->findOrFail($request->task));
        }
        if (isset($request->duplicate_of)) {
            $bug_report->setDuplicateOf($this->bugReportRepository->findOrFail($request->duplicate_of));
        }
        $this->entityManager->persist($bug_report);
        $this->entityManager->flush();

        return $bug_report;
    }

    public function create(BugReportCreateRequest $request): BugReport
    {

        $bug_report = new BugReport();

        $bug_report->setName($request->name);
        $bug_report->setText($request->text);
        $this->entityManager->persist($bug_report);
        $this->entityManager->flush();
        return $bug_report;
    }

    public function approveDuplicate($id): BugReport
    {
        $bug_report = $this->bugReportRepository->findOrFail($id);
        if (!(is_null($bug_report->getIsDuplicateOf()) || is_null($bug_report->getIsDuplicateOf()->getTask()))) {
            $task = $bug_report->getIsDuplicateOf()->getTask();
            $bug_report->setTask($task);
            $this->entityManager->persist($bug_report);
            $this->entityManager->flush();
            return $bug_report;
        }
        throw new ServiceException(400, ['message' => 'Not duplicate or duplicate task is not set'],);
    }

    public function declineDuplicate($id): BugReport
    {
        $bug_report = $this->bugReportRepository->findOrFail($id);
        if (is_null($bug_report->getIsDuplicateOf())){
            throw new ServiceException(400, ['message' => 'Is not duplicate'],);
        }
        if (is_null($bug_report->getTask())) {
            $bug_report->setIsDuplicateOf(null);
            $this->entityManager->persist($bug_report);
            $this->entityManager->flush();
            return $bug_report;
        }
        throw new ServiceException(400, ['message' => 'Already approved as duplicate or task is set'],);

    }
}
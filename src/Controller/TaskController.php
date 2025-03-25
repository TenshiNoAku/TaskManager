<?php

namespace App\Controller;

use App\Services\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/tasks')]

class TaskController extends AbstractController
{

    #[Route('/', name: 'task')]
    public function index (TaskService $taskService): JsonResponse
    {

        return $taskService->index();
    }
}
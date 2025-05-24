<?php

namespace App\Controller;


use App\DTO\Entity\ProjectEntityDTO;
use App\DTO\Entity\TasksEntityDTO;
use App\DTO\PaginatorMetaDTO;
use App\DTO\Requests\Create\ProjectCreateRequest;
use App\DTO\Requests\JsonApiResponse;
use App\DTO\Requests\Update\ProjectUpdateRequest;
use App\Entity\Project;
use App\Entity\Task;
use App\Services\ProjectService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/v1/projects')]
final class ProjectController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/', name: 'project_index', methods: ['GET'],)]

    public function index(#[MapQueryParameter] int $page=1, #[MapQueryParameter] int $per_page=100, #[MapQueryParameter] array $order = array(), #[MapQueryParameter] array $filter = array() ) : Response
    {
        $paginator = $this->entityManager->getRepository(Project::class)->findWithPagination($page,$per_page,$order,$filter);
        $data = new  ArrayCollection(iterator_to_array($paginator->getIterator()));
        $data = $data->map(function (Project $project) {
            $projectDTO = new ProjectEntityDTO($project);
            return $projectDTO->toArray();
        })->toArray();
        $meta = new PaginatorMetaDTO($paginator);
        return new JsonApiResponse(data:$data,meta:$meta->toArray());

    }



    #[Route('/{id}/', name: 'project_show', methods: ['GET'])]

    public function show($id): JsonResponse
    {
        $project = $this->entityManager->getRepository(Project::class)->findOrFail($id);
        $projectDTO = new ProjectEntityDTO($project);
        return new JsonApiResponse($projectDTO->toArray());
    }

    #[Route('/', name: 'project_create', methods: ['POST'])]

    public function create(ProjectService $service,
    #[MapRequestPayload] ProjectCreateRequest $projectCreateRequest): JsonResponse {

        $project = $service->create($projectCreateRequest);
        $projectDTO = new ProjectEntityDTO($project);
        return new JsonApiResponse($projectDTO->toArray());
    }

    #[Route('/{id}/', name: 'project_update', methods: ['PUT','PATCH'])]

    public function update(ProjectService $service,
                           #[MapRequestPayload] ProjectUpdateRequest $projectUpdateRequest,
                           $id) : JsonResponse{

        $project =  $service->update($projectUpdateRequest,$id);
        $projectDTO = new ProjectEntityDTO($project);
        return new JsonApiResponse($projectDTO->toArray());
    }


    #[Route('/{id}/', name: 'project_delete', methods: ['DELETE'])]
    public function delete(ProjectService $service,$id) : JsonResponse{
        $project = $service->delete($id);
        $projectDTO = new ProjectEntityDTO($project);
        return new JsonApiResponse($projectDTO->toArray());
    }




}

<?php

namespace App\Services;

use App\Entity\Project;
use App\Entity\Task;
use App\Traits\EntityNotFoundTrait;
use App\Traits\HandleIdInURLTrait;
use Composer\Semver\Constraint\Constraint;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProjectService
{
    use EntityNotFoundTrait;
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private RequestStack $requestStack;

    private ValidatorInterface $validator;


    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, RequestStack $requestStack, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->requestStack = $requestStack;
        $this->validator = $validator;
    }
    #TODO вынести валидацию в trait и составить адекватный json error body


    public function checkIdIsValid(string $id): bool
    {

    }



    public function index(): JsonResponse{
        $projects = $this->entityManager->getRepository(Project::class)->findAll();
        return JsonResponse::fromJsonString($this->serializer->serialize($projects, "json",
            [ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object){return $object->getId();},
                'json_encode_options'=> JSON_UNESCAPED_UNICODE,
                AbstractNormalizer::ATTRIBUTES => ['id','name'],]));

    }


    public function getById($id): Project
    {
        $project = $this->entityManager->getRepository(Project::class)->findOrFail($id);
        return $project;
    }

    public function getTasks($id): JsonResponse{
        $project = $this->getById($id);
        return JsonResponse::fromJsonString($this->serializer->serialize($project, "json"));

    }
}
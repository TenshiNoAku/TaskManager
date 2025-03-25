<?php

namespace App\Services;

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

class TaskService
{
    use EntityNotFoundTrait;
    use HandleIdInURLTrait;
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

    public function getById($id):
    JsonResponse{
        $path = $this->requestStack->getCurrentRequest()->getPathInfo();

        $errors = $this->handleId($id);
        if (!empty($errors)) {
            return new JsonResponse(['errors' => $errors], 400);
        }

        $task = $this->entityManager->getRepository(Task::class)->find($id);


        if(!$task){
            return $this->response404Exception($id, $path);
        }

        return JsonResponse::fromJsonString($this->serializer->serialize($task, "json", ['json_encode_options'=> JSON_UNESCAPED_UNICODE]));
    }

    public function index(): JsonResponse{
        $tasks = $this->entityManager->getRepository(Task::class)->findAll();

        return JsonResponse::fromJsonString($this->serializer->serialize($tasks, "json", ['json_encode_options'=> JSON_UNESCAPED_UNICODE,ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {}]));

    }



}
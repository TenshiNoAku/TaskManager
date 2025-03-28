<?php

namespace App\Traits;

use App\Services\ServiceException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait FindOfFailTrait
{
    public function findOrFail(string $id)
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();

        $violations = $validator->validate($id, [new Assert\Range(['min' => 0, 'max' => 2147483647])]);

        if (count($violations) !== 0) {
            throw new ServiceException(400,
                array('code'=>400,'message' => $violations[0]->getMessage(), 'invalidValue' => $id, 'propertyPath' => 'id')
                , '',null, ['Content-Type: application/json']);
        }

        $entity = $this->find($id);

        if (!$entity) {
            throw new ServiceException(404, [
                            "code" => 404,
                            "message" => "The requested resource with ID '$id' could not be found.",
                        ],''
                , null, ['Content-Type: application/json']);
        }

        return $entity;
    }
}
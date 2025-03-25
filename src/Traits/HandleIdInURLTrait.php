<?php

namespace App\Traits;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait HandleIdInURLTrait
{
    private function handleId(string $id): array {
        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();

        $violations = $validator->validate($id, [new Assert\Range(['min' => 0, 'max' => 2147483647])]);
        if (0 !== count($violations)) {
            // there are errors, now you can show them
            foreach ($violations as $violation) {
                return ['0'=>['invalidValue'=>$id, 'message'=>$violation->getMessage()]];
            }

        }
        return [];

    }
}
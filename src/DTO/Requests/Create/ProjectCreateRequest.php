<?php

namespace App\DTO\Requests\Create;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Optional;
class ProjectCreateRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 0, max:255)]
        public readonly string $name,

    ) {
    }
}
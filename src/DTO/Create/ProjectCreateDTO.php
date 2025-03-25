<?php

namespace App\DTO\Create;
use Symfony\Component\Validator\Constraints as Assert;
class ProjectCreateDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 0, max:255)]
        public readonly string $name,
    ) {
    }
}
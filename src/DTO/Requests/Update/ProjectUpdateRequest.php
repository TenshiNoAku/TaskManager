<?php
namespace App\DTO\Requests\Update;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectUpdateRequest

{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 0, max:255)]
        public readonly string $name
    )
    {

    }



}
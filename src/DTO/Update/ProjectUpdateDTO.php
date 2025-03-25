<?php
namespace App\DTO\Update;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectUpdateDTO

{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 0, max:255)]
        public readonly string $name
    )
    {

    }



}
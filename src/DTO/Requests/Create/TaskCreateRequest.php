<?php

namespace App\DTO\Requests\Create;
use Symfony\Component\Validator\Constraints as Assert;
class TaskCreateRequest

{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        public readonly string $name,

        #[Assert\NotBlank]
        #[Assert\Date]
        public readonly string $from_date,

        #[Assert\NotBlank]
        #[Assert\Date]
        #[Assert\GreaterThan(propertyPath: "from_date")]
        public readonly string $deadline,

        #[Assert\NotBlank]
        #[Assert\Time]
        public readonly string $time_cost,


        #[Assert\NotBlank]
        #[Assert\Type('int')]
        #[Assert\GreaterThan(0)]
        public readonly int $status_id,


        #[Assert\NotBlank]
        #[Assert\Type('int')]
        #[Assert\GreaterThan(0)]
        public readonly int $project_id,


        #[Assert\NotBlank]
        #[Assert\Type('int')]
        #[Assert\GreaterThan(0)]
        public readonly int $tracker_id,


        #[Assert\NotBlank]
        #[Assert\Type('int')]
        #[Assert\GreaterThan(0)]
        public readonly int $priority_id,


        #[Assert\Length(min: 0, max: 255)]
        public readonly string $description='',



    )
    {
    }
}
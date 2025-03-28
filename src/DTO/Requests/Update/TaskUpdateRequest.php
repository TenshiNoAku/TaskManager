<?php

namespace App\DTO\Requests\Update;
use Symfony\Component\Validator\Constraints as Assert;

class TaskUpdateRequest
{

    public function __construct(
        #[Assert\Length(min: 1, max: 255)]
        public readonly ?string $name = null,

        #[Assert\Date]
        public readonly ?string $from_date = null,

        #[Assert\Date]
        #[Assert\GreaterThan(propertyPath: "from_date")]
        public readonly ?string $deadline = null,

        #[Assert\Time]
        public readonly ?string $time_cost = null,


        #[Assert\Type('int')]
        #[Assert\GreaterThan(0)]
        public readonly ?int $status_id = null,


        #[Assert\Type('int')]
        #[Assert\GreaterThan(0)]
        public readonly ?int $project_id = null,


        #[Assert\Type('int')]
        #[Assert\GreaterThan(0)]
        public readonly ?int $tracker_id = null,


        #[Assert\Type('int')]
        #[Assert\GreaterThan(0)]
        public readonly ?int $priority_id = null,


        #[Assert\Length(min: 0, max: 255)]
        public readonly ?string $description=null,


    )
    {
    }


}
<?php

namespace App\DTO\Requests\Update;

class PriorityUpdateRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 0, max:255)]
        public readonly string $name
    )
    {

    }
}
<?php

namespace App\DTO\Requests\Create;

class PriorityCreateRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 0, max:255)]
        public readonly string $name,

    ) {
    }
}
<?php

namespace App\DTO\Requests\Create;
use Symfony\Component\Validator\Constraints as Assert;

class BugReportCreateRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 0, max:255)]
        public readonly string $name,

        #[Assert\NotBlank]
        #[Assert\Length(min: 0, max:2048)]
        public readonly string $text

    ) {
    }
}
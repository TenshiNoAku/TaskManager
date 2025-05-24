<?php

namespace App\DTO\Requests\Update;
use Symfony\Component\Validator\Constraints as Assert;
class BugReportUpdateRequest
{
    public function __construct(
        #[Assert\Length(min: 0, max:255)]
        public readonly ?string $name = null,

        #[Assert\Length(min: 0, max:2048)]
        public readonly ?string $text = null,

        #[Assert\Type('int')]
        #[Assert\GreaterThan(0)]
        public readonly ?int $duplicate_of = null,

        #[Assert\Type('int')]
        #[Assert\GreaterThan(0)]
        public readonly ?int $task = null,


    ) {
    }
}
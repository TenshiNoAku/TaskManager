<?php

namespace App\DTO\Entity;

use App\Entity\Priority;

class PriorityEntityDTO
{
    private int $id;
    private string $name;

    public function __construct(Priority $priority)
    {
        $this->id = $priority->getId();
        $this->name = $priority->getName();
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
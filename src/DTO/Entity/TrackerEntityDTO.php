<?php

namespace App\DTO\Entity;
use App\Entity\Tracker;

class TrackerEntityDTO
{
    private int $id;
    private string $name;

    public function __construct(Tracker $tracker)
    {
        $this->id = $tracker->getId();
        $this->name = $tracker->getName();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
<?php

namespace App\DTO\Entity;

use App\DTO\EntityDTOInterface;
use App\Entity\Project;
use App\Entity\Task;
use Doctrine\Common\Collections\Collection;

class ProjectEntityDTO implements EntityDTOInterface
{

    private int $id;
    private string $name;

    public function __construct(Project $project)
    {
        $this->id = $project->getId();
        $this->name = $project->getName();
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

}
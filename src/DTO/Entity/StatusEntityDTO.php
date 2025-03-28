<?php

namespace App\DTO\Entity;

use App\DTO\EntityDTOInterface;
use App\Entity\Status;
use App\Entity\Task;
use Doctrine\Common\Collections\Collection;

class StatusEntityDTO implements EntityDTOInterface
{

    private int $id;
    private string $name;

    private array $tasks;

    public function __construct(Status $status)
    {
        $this->id = $status->getId();
        $this->name = $status->getName();
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

}
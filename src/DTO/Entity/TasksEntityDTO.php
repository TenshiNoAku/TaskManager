<?php

namespace App\DTO\Entity;

use App\Entity\Project;
use App\Entity\Task;

class TasksEntityDTO
{
    private int $id;
    private string $name;
    private ProjectEntityDTO $project;
    private StatusEntityDTO $status;

    private PriorityEntityDTO $priority;

    private TrackerEntityDTO $tracker;

    public function __construct(Task $task)
    {
        $this->id = $task->getId();
        $this->name = $task->getName();
        $this->status = new StatusEntityDTO($task->getStatus());
        $this->project =new ProjectEntityDTO($task->getProject());;
        $this->tracker = new TrackerEntityDTO($task->getTracker());
        $this->priority = new PriorityEntityDTO($task->getPriority());

    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'project' => $this->project->toArray(),
            'status' => $this->status->toArray(),
            'tracker' => $this->tracker->toArray(),
            'priority' => $this->priority->toArray(),
        ];
    }
}
<?php

namespace App\DTO\Entity;

use App\Entity\BugReport;
use App\Entity\Priority;
use App\DTO\Entity\TasksEntityDTO;
class BugReportEntityDTO
{
    private int $id;
    private string $name;
    private string $text;

    private ?TasksEntityDTO $task;
    private ?BugReportEntityDTO $duplicate_of = null;




    public function __construct(BugReport $bugReport, $prevent_recursion = false)
    {
        $this->id = $bugReport->getId();
        $this->name = $bugReport->getName();
        $this->text = $bugReport->getText();

        $this->task = $bugReport->getTask() ? new TasksEntityDTO($bugReport->getTask()): null;
        if ($bugReport->getIsDuplicateOf() && !$prevent_recursion) {
            $this->duplicate_of = new BugReportEntityDTO($bugReport->getIsDuplicateOf(), true);
        }

    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'text' => $this->text,
            'task' => isset($this->task) ? $this->task->toArray(): null,
            'duplicate_of' => isset($this->duplicate_of) ?
                array('id'=>$this->duplicate_of->id,'name'=>$this->duplicate_of->name) : null,
        ];
    }
}
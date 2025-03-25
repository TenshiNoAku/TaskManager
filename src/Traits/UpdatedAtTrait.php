<?php

namespace App\Traits;
use Doctrine\ORM\Mapping as ORM;

trait UpdatedAtTrait
{
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    protected \DateTimeImmutable $updatedAtDatetime;

    public function getUpdatedAtDatetime(): \DateTimeImmutable
    {
        return $this->updatedAtDatetime;
    }
}
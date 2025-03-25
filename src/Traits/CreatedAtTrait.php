<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait CreatedAtTrait
{

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    protected \DateTimeImmutable $createdDatetime;

    public function getCreatedDatetime(): \DateTimeImmutable
    {
        return $this->createdDatetime;
    }
}
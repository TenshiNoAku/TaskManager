<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use App\Traits\GeneratedIdTrait;
use App\Traits\CreatedAtTrait;
use App\Traits\UpdatedAtTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrackerRepository::class)]
class Tracker
{
    use GeneratedIdTrait;
//    use CreatedAtTrait;
//    use UpdatedAtTrait;

    #[ORM\Column(length: 255)]
    private ?string $name = null;


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}

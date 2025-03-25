<?php

namespace App\Entity;

use App\Repository\PriorityRepository;
use App\Traits\GeneratedIdTrait;
use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PriorityRepository::class)]
class Priority implements JsonSerializable
{
    use GeneratedIdTrait;
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

    public function jsonSerialize(): mixed
    {
        return array(
            'id' => $this->id,
            'name' => $this->name
            );
    }
}

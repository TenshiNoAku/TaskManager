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

    #[ORM\Column(nullable: true)]
    private ?int $score = null;


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

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): static
    {
        $this->score = $score;

        return $this;
    }
}

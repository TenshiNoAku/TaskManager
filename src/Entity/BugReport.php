<?php

namespace App\Entity;

use App\Repository\BugReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BugReportRepository::class)]
class BugReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'bugReports')]
    private ?Task $task = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'duplicates')]
    private ?self $is_duplicate_of = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'is_duplicate_of')]
    private Collection $duplicates;

    public function __construct()
    {
        $this->duplicates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): static
    {
        $this->task = $task;

        return $this;
    }

    public function getIsDuplicateOf(): ?self
    {
        return $this->is_duplicate_of;
    }

    public function setIsDuplicateOf(?self $is_duplicate_of): static
    {
        $this->is_duplicate_of = $is_duplicate_of;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getDuplicates(): Collection
    {
        return $this->duplicates;
    }

    public function addDuplicate(self $duplicate): static
    {
        if (!$this->duplicates->contains($duplicate)) {
            $this->duplicates->add($duplicate);
            $duplicate->setIsDuplicateOf($this);
        }

        return $this;
    }

    public function removeDuplicate(self $duplicate): static
    {
        if ($this->duplicates->removeElement($duplicate)) {
            // set the owning side to null (unless already changed)
            if ($duplicate->getIsDuplicateOf() === $this) {
                $duplicate->setIsDuplicateOf(null);
            }
        }

        return $this;
    }

    public function __toString(): string {
        return $this->getId()." - ".$this->getName();
    }
}

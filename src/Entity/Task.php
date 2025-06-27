<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use App\Traits\GeneratedIdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    use GeneratedIdTrait;


    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\ManyToOne(fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false )]
    private ?Tracker $tracker = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]

    private ?Status $status = null;

    #[ORM\ManyToOne (fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Priority $priority = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fromDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan(propertyPath: "from_date")]
    private ?\DateTimeInterface $deadline = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $timeCost = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $created_by = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $developer = null;

    /**
     * @var Collection<int, BugReport>
     */
    #[ORM\OneToMany(targetEntity: BugReport::class, mappedBy: 'task')]
    private Collection $bugReports;

    #[ORM\Column]
    private ?int $confidence = 100;

    #[ORM\Column]
    private ?int $effort = 1;

    #[ORM\Column]
    private ?float $impact = 1;

    #[ORM\Column]
    private ?float $reach = 1;

    public function __construct()
    {
        $this->bugReports = new ArrayCollection();
    }


    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getTracker(): ?Tracker
    {
        return $this->tracker;
    }

    public function setTracker(?Tracker $tracker): static
    {
        $this->tracker = $tracker;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    public function setPriority(?Priority $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getFromDate(): ?\DateTimeInterface
    {
        return $this->fromDate;
    }

    public function setFromDate(\DateTimeInterface $fromDate): static
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTimeInterface $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getTimeCost(): ?\DateTimeInterface
    {
        return $this->timeCost;
    }

    public function setTimeCost(?\DateTimeInterface $timeCost): static
    {
        $this->timeCost = $timeCost;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): static
    {
        $this->created_by = $created_by;

        return $this;
    }

    public function getDeveloper(): ?User
    {
        return $this->developer;
    }

    public function setDeveloper(?User $developer): static
    {
        $this->developer = $developer;

        return $this;
    }

    /**
     * @return Collection<int, BugReport>
     */
    public function getBugReports(): Collection
    {
        return $this->bugReports;
    }

    public function addBugReport(BugReport $bugReport): static
    {
        if (!$this->bugReports->contains($bugReport)) {
            $this->bugReports->add($bugReport);
            $bugReport->setTask($this);
        }

        return $this;
    }

    public function removeBugReport(BugReport $bugReport): static
    {
        if ($this->bugReports->removeElement($bugReport)) {
            // set the owning side to null (unless already changed)
            if ($bugReport->getTask() === $this) {
                $bugReport->setTask(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getId()." - ".$this->getName();
    }

    public function getConfidence(): ?int
    {
        return $this->confidence;
    }

    public function setConfidence(int $confidence): static
    {
        $this->confidence = $confidence;

        return $this;
    }

    public function getEffort(): ?int
    {
        return $this->effort;
    }

    public function setEffort(int $effort): static
    {
        $this->effort = $effort;

        return $this;
    }

    public function getImpact(): ?float
    {
        return $this->impact;
    }

    public function setImpact(float $impact): static
    {
        $this->impact = $impact;

        return $this;
    }

    public function getReach(): ?float
    {
        return $this->reach;
    }

    public function setReach(float $reach): static
    {
        $this->reach = $reach;

        return $this;
    }

    public function calculatePriority(EntityManagerInterface $entityManager): static
    {
        $score = round(($this->reach * (1+0.5*log(1+0.4*count($this->bugReports))) * $this->confidence)/$this->effort);

        $priority = $entityManager->getRepository(Priority::class)->createQueryBuilder('e')
            ->where('e.score <= :score')
            ->setParameter('score', $score)
            ->orderBy('e.score', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        $this->setPriority($priority[0]);
        $entityManager->persist($this);
        $entityManager->flush();

        return $this;
    }
}

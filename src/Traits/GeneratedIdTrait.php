<?php

namespace App\Traits;
use Doctrine\ORM\Mapping as ORM;

trait GeneratedIdTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    public function getId(): ?int
    {
        return $this->id;
    }
}
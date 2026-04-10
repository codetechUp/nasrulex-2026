<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EnvRepository")
 */
class Env
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $free;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFree(): ?bool
    {
        return $this->free;
    }

    public function setFree(?bool $free): self
    {
        $this->free = $free;

        return $this;
    }
}

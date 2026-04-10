<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 */
class Document
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="blob")
     */
    private $fichier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="docs")
     */
    private $categorie;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SousCategorie", inversedBy="documents")
     */
    private $souscat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nature="YES";

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Nature", inversedBy="documents")
     */
    private $natures;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isFree;

   
   

    public function __construct()
    {
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFichier()
    {
        return $this->fichier;
    }

    public function setFichier($fichier): self
    {
        $this->fichier = $fichier;

        return $this;
    }

   

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getSouscat(): ?SousCategorie
    {
        return $this->souscat;
    }

    public function setSouscat(SousCategorie $souscat): self
    {
        $this->souscat = $souscat;

        return $this;
    }

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(string $nature): self
    {
        $this->nature = $nature;

        return $this;
    }

    public function getNatures(): ?Nature
    {
        return $this->natures;
    }

    public function setNatures(?Nature $natures): self
    {
        $this->natures = $natures;

        return $this;
    }

    public function getIsFree(): ?bool
    {
        return $this->isFree;
    }

    public function setIsFree(?bool $isFree): self
    {
        $this->isFree = $isFree;

        return $this;
    }
}

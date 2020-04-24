<?php

namespace App\Entity;

use App\Entity\Photo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank( message = "Vous devez saisir un nom pour ce produit." )
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank( message = "Vous devez saisir une description pour ce produit." )
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="product")
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\State", mappedBy="product")
     */
    private $states;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->states = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setProduct($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getProduct() === $this) {
                $photo->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|State[]
     */
    public function getStates(): Collection
    {
        return $this->states;
    }

    public function addState(State $state): self
    {
        if (!$this->states->contains($state)) {
            $this->states[] = $state;
            $state->setProduct($this);
        }

        return $this;
    }

    public function removeState(State $state): self
    {
        if ($this->states->contains($state)) {
            $this->states->removeElement($state);
            // set the owning side to null (unless already changed)
            if ($state->getProduct() === $this) {
                $state->setProduct(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->id .' - '. $this->name;
    }
}

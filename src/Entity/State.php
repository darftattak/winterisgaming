<?php

namespace App\Entity;

use App\Entity\OrderHasProduct;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StateRepository")
 */
class State
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank( message = "Vous devez saisir un prix" )
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stock;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $state;

    /**
     * @Assert\NotBlank( message = "Vous devez sélectionner un produit" )
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="states")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderHasProduct", mappedBy="stateProductId")
     */
    private $orderHasProducts;

    public function __construct()
    {
        $this->orderHasProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Collection|OrderHasProduct[]
     */
    public function getOrderHasProducts(): Collection
    {
        return $this->orderHasProducts;
    }

    public function addOrderHasProduct(OrderHasProduct $orderHasProduct): self
    {
        if (!$this->orderHasProducts->contains($orderHasProduct)) {
            $this->orderHasProducts[] = $orderHasProduct;
            $orderHasProduct->setStateProductId($this);
        }

        return $this;
    }

    public function removeOrderHasProduct(OrderHasProduct $orderHasProduct): self
    {
        if ($this->orderHasProducts->contains($orderHasProduct)) {
            $this->orderHasProducts->removeElement($orderHasProduct);
            // set the owning side to null (unless already changed)
            if ($orderHasProduct->getStateProductId() === $this) {
                $orderHasProduct->setStateProductId(null);
            }
        }

        return $this;
    }

    public function __toString()
    {   
        if($this->getStock() == 0) {
            return "Epuisé - " .$this->state . ' - ' . $this->price .'€';
        } else {
            return $this->state . ' - ' . $this->price .'€ - '. $this->stock. ' copies restantes';
        }
        
    }
}

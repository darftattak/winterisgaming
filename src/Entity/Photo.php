<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 */
class Photo
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
    private $picture;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @Assert\Expression(
     *      "this.getPicture() or this.getPictureFile()",
     *      message = "Vous devez sélectionner une image pour votre événement",
     * )
     * @Assert\File(
     *      maxSize = "5M",
     *      maxSizeMessage = "Votre fichier est trop lourd, il ne doit pas dépasser {{ limit }}{{ suffix }}.",
     *      mimeTypes = {"image/png", "image/jpeg"},
     *      mimeTypesMessage = "Seules les images PNG et JPEG/JPG sont autorisées.",
     * )
     */
    private $pictureFile;

    private $picturePath;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    
    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

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

    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }

    public function setPictureFile(File $pictureFile): self
    {
        $this->pictureFile = $pictureFile;

        return $this;
    }

    public function setPicturePath(): self
    {
        $name = preg_split( "/public\/data\\\/",$this->getPicture());
        if(!empty($name[1])){
            $picturePath = $name[1];
        }else{
            $name = preg_split( "/public\/data\//",$this->getPicture());
            $picturePath=$name[1];
        }
        $this->picturePath = $picturePath;

        return $this;
    }

    public function getPicturePath(): ?string
    {
        return $this->picturePath;
    }

}

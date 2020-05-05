<?php

namespace App\Entity;

use App\Entity\Product;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity("username", message="Ce nom d'utilisateur est déjà pris")
 * @UniqueEntity("email", message="Cette adresse mail est déjà prise")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank( message = "Vous devez saisir un prénom" )
     * @Assert\Length (
     *      min = 2,
     *      max = 50,
     *      minMessage ="Le prénom doit comporter au minimum {{ limit }} caractères.",
     *      maxMessage ="Le prénom doit comporter au maximum {{ limit }} caractères.",
     * )
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $firstname;

    /**
     * @Assert\NotBlank( message = "Vous devez saisir un prénom" )
     * @Assert\Length (
     *      min = 2,
     *      max = 50,
     *      minMessage ="Le prénom doit comporter au minimum {{ limit }} caractères.",
     *      maxMessage ="Le prénom doit comporter au maximum {{ limit }} caractères.",
     * )
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $lastname;

    /**
     * @Assert\NotBlank( message = "Vous devez saisir un pseudo" )
     * @Assert\Length (
     *      min = 3,
     *      max = 25,
     *      minMessage ="Le pseudo doit comporter au minimum {{ limit }} caractères.",
     *      maxMessage ="Le pseudo doit comporter au maximum {{ limit }} caractères.",
     * )
     * @ORM\Column(type="string", length=50)
     */
    private $username;

    /**
     * @Assert\NotBlank( message = "Vous devez saisir une adresse mail" )
     * @Assert\Email (
     *      message="L'adresse mail n'est pas valide"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $loyalty;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Address", mappedBy="user")
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="user")
     */
    private $orders;

    /**
     * @Assert\Expression(
     *     "this.getPassword() or this.getPlainPassword()",
     *     message="Vous devez saisir un mot de passe"
     * )
     * @Assert\Length (
     *      min = 6,
     *      max = 16,
     *      minMessage ="Votre mot de passe doit comporter au minimum {{ limit }} caractères.",
     *      maxMessage ="Votre mot de passe doit comporter au maximum {{ limit }} caractères.",
     * )
     */
    private $plainPassword;

    /**
     * @Assert\File(
     *      maxSize = "2M",
     *      maxSizeMessage = "Votre fichier est trop lourd, il ne doit pas dépasser {{ limit }}{{ suffix }}.",
     *      mimeTypes = {"image/png", "image/jpeg"},
     *      mimeTypesMessage = "Seules les images PNG et JPEG/JPG sont autorisées.",
     * )
     */
    private $avatarFile;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="users")
     */
    private $wishlist;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->wishlist = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getLoyalty(): ?int
    {
        return $this->loyalty;
    }

    public function setLoyalty(?int $loyalty): self
    {
        $this->loyalty = $loyalty;

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    public function setAvatarFile(File $avatarFile = null): self
    {
        $this->avatarFile = $avatarFile;

        return $this;
    }
    public function getSalt() {
        return null;
    }

    public function eraseCredentials() {}

    /**
     * @return Collection|Product[]
     */
    public function getWishlist(): Collection
    {
        return $this->wishlist;
    }

    public function addWishlist(Product $wishlist): self
    {
        if (!$this->wishlist->contains($wishlist)) {
            $this->wishlist[] = $wishlist;
        }

        return $this;
    }

    public function removeWishlist(Product $wishlist): self
    {
        if ($this->wishlist->contains($wishlist)) {
            $this->wishlist->removeElement($wishlist);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->avatar,
            $this->email,
            $this->addresses,
            $this->wishlist,
            $this->firstname,
            $this->lastname,
            $this->roles,
            $this->loyalty,
            $this->orders,
        ));
    }
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->avatar,
            $this->email,
            $this->addresses,
            $this->wishlist,
            $this->firstname,
            $this->lastname,
            $this->roles,
            $this->loyalty,
            $this->orders,
        ) = unserialize($serialized);
    }
}

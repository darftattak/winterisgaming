<?php

namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;


class Contact
{
    /**
     * @Assert\NotBlank( message = "Vous devez saisir un prénom" )
     * @Assert\Length (
     *      min = 2,
     *      max = 50,
     *      minMessage ="Le prénom doit comporter au minimum {{limit}} caractères.",
     *      maxMessage ="Le prénom doit comporter au maximum {{limit}} caractères.",
     * )
     */
    private $firstname;

    /**
     * @Assert\NotBlank( message = "Vous devez saisir un nom" )
     * @Assert\Length (
     *      min = 2,
     *      max = 50,
     *      minMessage ="Le nom doit comporter au minimum {{limit}} caractères.",
     *      maxMessage ="Le nom doit comporter au maximum {{limit}} caractères.",
     * )
     */
    private $lastname;

    /**
     * @Assert\NotBlank( message = "Vous devez saisir une adresse mail" )
     * @Assert\Email (
     *      message="L'adresse mail n'est pas valide"
     * )
     */
    private $email;

    /**
     * @Assert\NotBlank( message = "Vous devez choisir un sujet" )
     */
    private $topic;

    /**
     * 
     */
    private $user;

    /**
     * @Assert\NotBlank( message = "Vous devez saisir un message" )
     * @Assert\Length (
     *      min = 100,
     *      max = 2500,
     *      minMessage ="votre message doit comporter au minimum {{limit}} caractères.",
     *      maxMessage ="votre message doit comporter au maximum {{limit}} caractères.",
     * )
     */
    private $message;

    /**
     * 
     */
    private $sentAt;

    /**
     * 
     */
    private $orderNumber;

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(int $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeInterface $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(string $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }
}

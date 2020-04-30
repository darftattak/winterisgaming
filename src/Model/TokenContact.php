<?php

namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;


class TokenContact
{
    /**
     * @Assert\NotBlank( message = "Vous devez saisir une adresse mail" )
     * @Assert\Email (
     *      message = "L'adresse mail n'est pas valide"
     * )
     */
    private $email;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}

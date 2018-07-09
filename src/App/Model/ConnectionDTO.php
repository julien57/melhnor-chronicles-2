<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ConnectionDTO
{
    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param null|string $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param null|string $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="avatar")
 * @ORM\Entity(repositoryClass="App\Repository\AvatarRepository")
 */
class Avatar
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="id_avatar", type="string")
     */
    private $idAvatar;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getIdAvatar(): ?string
    {
        return $this->idAvatar;
    }

    /**
     * @param string|null $idAvatar
     */
    public function setIdAvatar(?string $idAvatar): void
    {
        $this->idAvatar = $idAvatar;
    }
}

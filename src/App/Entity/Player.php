<?php

namespace App\Entity;

use App\Model\CreatePlayerDTO;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="player")
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player implements UserInterface
{
    const USERNAME_ROLE_ADMIN = 'julien.montel@sfr.fr';

    const ACTION_POINTS_STARTER = 50;

    const ACTION_POINTS_FOR_PRODUCTION = 10;

    const ACTION_POINTS_FOR_BATTLE = 10;

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
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mail", type="string", length=255, unique=true)
     */
    private $mail;

    /**
     * @var int|null
     *
     * @ORM\Column(name="action_points", type="integer")
     */
    private $actionPoints = self::ACTION_POINTS_STARTER;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var bool
     *
     * @ORM\Column(name="is_online", type="boolean")
     */
    private $isOnline;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_registration", type="datetime")
     */
    private $dateRegistration;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_connection", type="datetime")
     */
    private $lastConnection;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_not_read", type="boolean")
     */
    private $isNotRead = false;

    /**
     * @var Avatar
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Avatar", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $avatar;

    /**
     * @var Kingdom
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Kingdom", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $kingdom;

    public function __construct()
    {
        $this->dateRegistration = new \DateTime();
        $this->lastConnection = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * @param null|string $mail
     */
    public function setMail(?string $mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @return int|null
     */
    public function getActionPoints(): ?int
    {
        return $this->actionPoints;
    }

    /**
     * @param int|null $actionPoints
     */
    public function setActionPoints(?int $actionPoints): void
    {
        $this->actionPoints = $actionPoints;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateRegistration(): ?\DateTime
    {
        return $this->dateRegistration;
    }

    /**
     * @param \DateTime|null $dateRegistration
     */
    public function setDateRegistration(?\DateTime $dateRegistration): void
    {
        $this->dateRegistration = $dateRegistration;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastConnection(): ?\DateTime
    {
        return $this->lastConnection;
    }

    /**
     * @param \DateTime|null $lastConnection
     */
    public function setLastConnection(?\DateTime $lastConnection): void
    {
        $this->lastConnection = $lastConnection;
    }

    /**
     * @return Avatar
     */
    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    /**
     * @param Avatar $avatar
     */
    public function setAvatar(Avatar $avatar): void
    {
        $this->avatar = $avatar;
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
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return Kingdom
     */
    public function getKingdom(): ?Kingdom
    {
        return $this->kingdom;
    }

    /**
     * @return bool
     */
    public function isNotRead(): bool
    {
        return $this->isNotRead;
    }

    /**
     * @param bool $isNotRead
     */
    public function setIsNotRead(bool $isNotRead): void
    {
        $this->isNotRead = $isNotRead;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;

        $this->password = null;
    }

    /**
     * @return bool
     */
    public function isOnline(): bool
    {
        $now = new \DateTime();
        $now->modify('-5 minutes');

        return $this->lastConnection > $now;
    }

    /**
     * @param bool $isOnline
     */
    public function setIsOnline(bool $isOnline): void
    {
        $this->isOnline = $isOnline;
    }

    /**
     * @param null|string $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param Kingdom $kingdom
     */
    public function setKingdom(Kingdom $kingdom): void
    {
        $this->kingdom = $kingdom;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $roles = $this->roles;

        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        return $roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public static function initPlayer(CreatePlayerDTO $createPlayerDTO, Kingdom $kingdom): self
    {
        $player = new self();
        $player->username = $createPlayerDTO->getUsername();
        $player->plainPassword = $createPlayerDTO->getPassword();
        $player->mail = $createPlayerDTO->getMail();
        $player->avatar = $createPlayerDTO->getAvatar();
        $player->roles = ['ROLE_PLAYER'];
        $player->isOnline = true;
        $player->kingdom = $kingdom;

        return $player;
    }
}

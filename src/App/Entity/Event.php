<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    const DRAGON_PRICE_BATTLE = 5;
    const FLAMETHROWER_ATTACK_NAME = 'Attaque de feu';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="difficulty", type="string")
     */
    private $difficulty;

    /**
     * @var int
     *
     * @ORM\Column(name="power", type="integer")
     */
    private $power;

    /**
     * @var int
     *
     * @ORM\Column(name="special_attack", type="integer")
     */
    private $specialAttack;

    /**
     * @var int
     *
     * @ORM\Column(name="life", type="integer")
     */
    private $life;

    /**
     * @var string
     *
     * @ORM\Column(name="img_min", type="string")
     */
    private $imgMin;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string")
     */
    private $img;

    /**
     * @var int
     *
     * @ORM\Column(name="ap", type="integer")
     */
    private $ap;

    /**
     * @var KingdomEvent
     *
     * @ORM\OneToMany(targetEntity="App\Entity\KingdomEvent", mappedBy="event")
     */
    private $kingdomEvents;

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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getPower(): int
    {
        return $this->power;
    }

    /**
     * @param int $power
     */
    public function setPower(int $power): void
    {
        $this->power = $power;
    }

    /**
     * @return int
     */
    public function getLife(): int
    {
        return $this->life;
    }

    /**
     * @param int $life
     */
    public function setLife(int $life): void
    {
        $this->life = $life;
    }

    /**
     * @return string
     */
    public function getImg(): string
    {
        return $this->img;
    }

    /**
     * @param string $img
     */
    public function setImg(string $img): void
    {
        $this->img = $img;
    }

    /**
     * @return string
     */
    public function getImgMin(): string
    {
        return $this->imgMin;
    }

    /**
     * @param string $imgMin
     */
    public function setImgMin(string $imgMin): void
    {
        $this->imgMin = $imgMin;
    }

    /**
     * @return int
     */
    public function getAp(): int
    {
        return $this->ap;
    }

    /**
     * @param int $ap
     */
    public function setAp(int $ap): void
    {
        $this->ap = $ap;
    }

    /**
     * @return string
     */
    public function getDifficulty(): string
    {
        return $this->difficulty;
    }

    /**
     * @param string $difficulty
     */
    public function setDifficulty(string $difficulty): void
    {
        $this->difficulty = $difficulty;
    }

    /**
     * @return int
     */
    public function getSpecialAttack(): int
    {
        return $this->specialAttack;
    }

    /**
     * @param int $specialAttack
     */
    public function setSpecialAttack(int $specialAttack): void
    {
        $this->specialAttack = $specialAttack;
    }

    /**
     * @return KingdomEvent
     */
    public function getKingdomEvents(): KingdomEvent
    {
        return $this->kingdomEvents;
    }

    /**
     * @param KingdomEvent $kingdomEvents
     */
    public function setKingdomEvents(KingdomEvent $kingdomEvents): void
    {
        $this->kingdomEvents = $kingdomEvents;
    }
}

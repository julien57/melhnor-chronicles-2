<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Event
 *
 * @ORM\Table(name="kingdom_event")
 * @ORM\Entity(repositoryClass="App\Repository\KingdomEventRepository")
 */
class KingdomEvent
{
    const START_DAMAGE_NUMBER = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="damage", type="integer")
     */
    private $damage = self::START_DAMAGE_NUMBER;

    /**
     * @var Kingdom
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Kingdom", inversedBy="kingdomEvent")
     */
    private $kingdom;

    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="kingdomEvents")
     */
    private $event;

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
     * @return int
     */
    public function getDamage(): int
    {
        return $this->damage;
    }

    /**
     * @param int $damage
     */
    public function setDamage(int $damage): void
    {
        $this->damage = $damage;
    }

    /**
     * @return Kingdom
     */
    public function getKingdom(): Kingdom
    {
        return $this->kingdom;
    }

    /**
     * @param Kingdom $kingdom
     */
    public function setKingdom(Kingdom $kingdom): void
    {
        $this->kingdom = $kingdom;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Army
 *
 * @ORM\Table(name="army")
 * @ORM\Entity(repositoryClass="App\Repository\ArmyRepository")
 */
class Army
{
    const START_ARMY_NUMBER = 0;

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
     * @ORM\Column(name="soldier", type="integer")
     */
    private $soldier = self::START_ARMY_NUMBER;

    /**
     * @var int
     *
     * @ORM\Column(name="archer", type="integer")
     */
    private $archer = self::START_ARMY_NUMBER;

    /**
     * @var int
     *
     * @ORM\Column(name="boat", type="integer")
     */
    private $boat = self::START_ARMY_NUMBER;

    /**
     * @var Kingdom
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Kingdom", inversedBy="army")
     */
    private $kingdom;

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
    public function getSoldier(): int
    {
        return $this->soldier;
    }

    /**
     * @param int $soldier
     */
    public function setSoldier(int $soldier): void
    {
        $this->soldier = $soldier;
    }

    /**
     * @return int
     */
    public function getArcher(): int
    {
        return $this->archer;
    }

    /**
     * @param int $archer
     */
    public function setArcher(int $archer): void
    {
        $this->archer = $archer;
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
     * @return int
     */
    public function getBoat(): int
    {
        return $this->boat;
    }

    /**
     * @param int $boat
     */
    public function setBoat(int $boat): void
    {
        $this->boat = $boat;
    }

    static public function initArmy()
    {
        $army = new self();

        $army->soldier = self::START_ARMY_NUMBER;
        $army->archer = self::START_ARMY_NUMBER;
        $army->boat = self::START_ARMY_NUMBER;

        return $army;
    }
}

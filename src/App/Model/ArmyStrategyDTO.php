<?php

namespace App\Model;

use App\Entity\Army;
use Symfony\Component\Validator\Constraints as Assert;

class ArmyStrategyDTO
{
    /**
     * @var Army
     */
    private $army;

    /**
     * @var int|null
     *
     * @Assert\Type("integer")
     */
    private $archer;

    /**
     * @var int|null
     *
     * @Assert\Type("integer")
     */
    private $soldier;

    /**
     * @var int|null
     *
     * @Assert\Type("integer")
     */
    private $horseman;

    /**
     * @var int|null
     *
     * @Assert\Type("integer")
     */
    private $boat;

    /**
     * @return Army
     */
    public function getArmy(): Army
    {
        return $this->army;
    }

    /**
     * @param Army $army
     */
    public function setArmy(Army $army): void
    {
        $this->army = $army;
    }

    /**
     * @return int|null
     */
    public function getSoldier(): ?int
    {
        return $this->soldier;
    }

    /**
     * @param int|null $soldier
     */
    public function setSoldier(?int $soldier): void
    {
        $this->soldier = $soldier;
    }

    /**
     * @return int|null
     */
    public function getArcher(): ?int
    {
        return $this->archer;
    }

    /**
     * @param int|null $archer
     */
    public function setArcher(?int $archer): void
    {
        $this->archer = $archer;
    }

    /**
     * @return int|null
     */
    public function getBoat(): ?int
    {
        return $this->boat;
    }

    /**
     * @param int|null $boat
     */
    public function setBoat(?int $boat): void
    {
        $this->boat = $boat;
    }

    /**
     * @return int|null
     */
    public function getHorseman(): ?int
    {
        return $this->horseman;
    }

    /**
     * @param int|null $horseman
     */
    public function setHorseman(?int $horseman): void
    {
        $this->horseman = $horseman;
    }
}

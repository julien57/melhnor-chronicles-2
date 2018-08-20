<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="kingdom_army")
 * @ORM\Entity(repositoryClass="App\Repository\ArmyRegionRepository")
 */
class KingdomArmy
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
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var Army
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Army", inversedBy="kingdomArmys")
     * @ORM\JoinColumn(nullable=false)
     */
    private $army;

    /**
     * @var Kingdom
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Kingdom", inversedBy="kingdomArmys")
     * @ORM\JoinColumn(nullable=false)
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
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

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
}

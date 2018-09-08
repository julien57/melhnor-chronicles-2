<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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

    const SOLDIER_ID = 1;

    const ARCHER_ID = 2;

    const HORSEMAN_ID = 3;

    const BOAT_ID = 4;

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
     * @ORM\Column(name="img", type="string")
     */
    private $img;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="KingdomArmy", mappedBy="army", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    private $kingdomArmys;

    public function __construct()
    {
        $this->kingdomArmys = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getKingdomArmys(): ArrayCollection
    {
        return $this->kingdomArmys;
    }

    /**
     * @param ArrayCollection $kingdomArmys
     */
    public function setKingdomArmys(ArrayCollection $kingdomArmys): void
    {
        $this->kingdomArmys = $kingdomArmys;
    }
}

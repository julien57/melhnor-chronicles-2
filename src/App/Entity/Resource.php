<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="resource")
 * @ORM\Entity(repositoryClass="App\Repository\ResourceRepository")
 */
class Resource
{
    const MEAT_ID = 1;

    const WOOD_ID = 24;

    const STONE_ID = 23;

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_food", type="boolean")
     */
    private $isFood = false;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\BuildingResource",
     *     mappedBy="resource",
     *     fetch="EXTRA_LAZY"
     * )
     */
    private $buildingResources;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\KingdomResource",
     *     mappedBy="resource",
     *     fetch="EXTRA_LAZY"
     * )
     */
    private $kingdomResources;

    public function __construct()
    {
        $this->kingdomResources = new ArrayCollection();
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
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return bool
     */
    public function isFood(): bool
    {
        return $this->isFood;
    }

    /**
     * @param bool $isFood
     */
    public function setIsFood(bool $isFood): void
    {
        $this->isFood = $isFood;
    }

    /**
     * @return ArrayCollection
     */
    public function getBuildingResources(): ArrayCollection
    {
        return $this->buildingResources;
    }

    /**
     * @param ArrayCollection $buildingResources
     */
    public function setBuildingResources(ArrayCollection $buildingResources): void
    {
        $this->buildingResources = $buildingResources;
    }

    /**
     * @return ArrayCollection
     */
    public function getKingdomResources(): ArrayCollection
    {
        return $this->kingdomResources;
    }
}

<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="building")
 * @ORM\Entity(repositoryClass="App\Repository\BuildingRepository")
 */
class Building
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var KingdomBuilding
     *
     * @ORM\OneToMany(targetEntity="App\Entity\KingdomBuilding", mappedBy="building")
     */
    private $kingdomBuildings;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\BuildingResource",
     *     mappedBy="building",
     *     fetch="EXTRA_LAZY"
     * )
     */
    private $buildingResources;

    public function __construct()
    {
        $this->kingdomBuildings = new ArrayCollection();
        $this->buildingResources = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @param KingdomBuilding $kingdomBuilding
     *
     * @return $this
     */
    public function addKingdomBuilding(KingdomBuilding $kingdomBuilding)
    {
        $this->kingdomBuildings[] = $kingdomBuilding;

        return $this;
    }

    /**
     * @param KingdomBuilding $kingdomBuilding
     */
    public function removeKingdomBuilding(KingdomBuilding $kingdomBuilding)
    {
        $this->kingdomBuildings->removeElement($kingdomBuilding);
    }

    /**
     * @return KingdomBuilding|ArrayCollection
     */
    public function getKingdomBuildings()
    {
        return $this->kingdomBuildings;
    }

    /**
     * @param BuildingResource $buildingResource
     *
     * @return $this
     */
    public function addBuildingResource(BuildingResource $buildingResource)
    {
        $this->buildingResources[] = $buildingResource;

        return $this;
    }

    /**
     * @param BuildingResource $buildingResource
     */
    public function removeBuildingResource(BuildingResource $buildingResource)
    {
        $this->buildingResources->removeElement($buildingResource);
    }

    /**
     * @return ArrayCollection
     */
    public function getBuildingResources()
    {
        return $this->buildingResources;
    }
}

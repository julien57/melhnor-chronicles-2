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
}

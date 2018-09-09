<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="region")
 * @ORM\Entity(repositoryClass="App\Repository\RegionRepository")
 */
class Region
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
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     */
    private $picture;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var Building
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Building")
     */
    private $buildings;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="KingdomArmy", mappedBy="region", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    private $armysRegion;

    public function __construct()
    {
        $this->buildings = new ArrayCollection();
        $this->armysRegion = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * Add building
     *
     * @param Building $building
     *
     * @return Region
     */
    public function addBuilding(Building $building)
    {
        $this->buildings[] = $building;

        return $this;
    }

    /**
     * Remove building
     *
     * @param Building $building
     */
    public function removeBuilding(Building $building)
    {
        $this->buildings->removeElement($building);
    }

    /**
     * Get buildings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBuildings()
    {
        return $this->buildings;
    }

    /**
     * @return string
     */
    public function getPicture(): string
    {
        return $this->picture;
    }

    /**
     * @param string $picture
     */
    public function setPicture(string $picture): void
    {
        $this->picture = $picture;
    }
}

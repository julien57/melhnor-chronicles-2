<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class BuildingResource
{
    const PRODUCTION_RESOURCE_BUILDIND = false;

    const REQUIRED_RESOURCE_BUILDING = false;

    /**
     * @var Building
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Building", inversedBy="buildingResources", cascade={"persist"})
     * @ORM\JoinColumn(name="building_id", referencedColumnName="id")
     * @ORM\Id()
     */
    private $building;

    /**
     * @var resource
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Resource", inversedBy="buildingResources")
     * @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     * @ORM\Id()
     */
    private $resource;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isProduction = self::PRODUCTION_RESOURCE_BUILDIND;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isRequired = self::REQUIRED_RESOURCE_BUILDING;

    /**
     * @return Building
     */
    public function getBuilding(): Building
    {
        return $this->building;
    }

    /**
     * @param Building $building
     */
    public function setBuilding(Building $building): void
    {
        $this->building = $building;
    }

    /**
     * @return resource
     */
    public function getResource(): Resource
    {
        return $this->resource;
    }

    /**
     * @param resource $resource
     */
    public function setResource(Resource $resource): void
    {
        $this->resource = $resource;
    }

    /**
     * @return bool
     */
    public function isProduction(): bool
    {
        return $this->isProduction;
    }

    /**
     * @param bool $isProduction
     */
    public function setIsProduction(bool $isProduction): void
    {
        $this->isProduction = $isProduction;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * @param bool $isRequired
     */
    public function setIsRequired(bool $isRequired): void
    {
        $this->isRequired = $isRequired;
    }
}

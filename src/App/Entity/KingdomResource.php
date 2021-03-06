<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="kingdom_resource")
 * @ORM\Entity(repositoryClass="App\Repository\KingdomResourceRepository")
 */
class KingdomResource
{
    const MEAT_STARTER_QUANTITY = 10000;

    const WOOD_STARTER_QUANTITY = 3000;

    const STONE_STARTER_QUANTITY = 3000;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var Kingdom
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Kingdom", inversedBy="kingdomResources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $kingdom;

    /**
     * @var resource
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Resource", inversedBy="kingdomResources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $resource;

    public function __construct(Kingdom $kingdom, Resource $resource, int $quantity)
    {
        $this->kingdom = $kingdom;
        $this->resource = $resource;
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
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
}

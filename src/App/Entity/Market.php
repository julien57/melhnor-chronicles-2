<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="market")
 * @ORM\Entity(repositoryClass="")
 */
class Market
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
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var KingdomResource
     *
     * @ORM\OneToMany(targetEntity="App\Entity\KingdomResource", mappedBy="market")
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
     * @return KingdomResource
     */
    public function getKingdomResources(): KingdomResource
    {
        return $this->kingdomResources;
    }

    /**
     * @param KingdomResource $kingdomResources
     */
    public function setKingdomResources(KingdomResource $kingdomResources): void
    {
        $this->kingdomResources = $kingdomResources;
    }
}

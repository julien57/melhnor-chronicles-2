<?php

namespace App\Entity;

use App\Controller\Game\sellResourceController;
use App\Entity\KingdomResource;
use App\Model\SaleResourceDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="market")
 * @ORM\Entity(repositoryClass="App\Repository\MarketRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\KingdomResource", cascade={"persist"})
     */
    private $kingdomResource;

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
     * @return \App\Entity\KingdomResource
     */
    public function getKingdomResource(): \App\Entity\KingdomResource
    {
        return $this->kingdomResource;
    }

    /**
     * @param \App\Entity\KingdomResource $kingdomResource
     */
    public function setKingdomResource(\App\Entity\KingdomResource $kingdomResource): void
    {
        $this->kingdomResource = $kingdomResource;
    }

    public static function saleResource(SaleResourceDTO $saleResourceDTO, int $sellingPrice, KingdomResource $kingdomResource)
    {
        $market = new self();

        $market->quantity = $saleResourceDTO->getQuantity();
        $market->price = $sellingPrice;
        $market->kingdomResource = $kingdomResource;

        return $market;
    }
}

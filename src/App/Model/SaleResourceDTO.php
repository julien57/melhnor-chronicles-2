<?php

namespace App\Model;

use App\Entity\Resource;
use Symfony\Component\Validator\Constraints as Assert;

class SaleResourceDTO
{
    /**
     * @var resource|null
     */
    private $resource;

    /**
     * @var int|null
     * @Assert\LessThan(2147483647, message="Quantité trop élevé.")
     */
    private $quantity;

    /**
     * @return null|resource
     */
    public function getResource(): ?Resource
    {
        return $this->resource;
    }

    /**
     * @param null|resource $resource
     */
    public function setResource(?Resource $resource): void
    {
        $this->resource = $resource;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int|null $quantity
     */
    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }
}

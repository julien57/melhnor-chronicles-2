<?php

namespace App\Model;

use App\Entity\Resource;

class SaleResourceDTO
{
    /**
     * @var Resource|null
     */
    private $resource;

    /**
     * @var int|null
     */
    private $quantity;

    /**
     * @return null|Resource
     */
    public function getResource(): ?Resource
    {
        return $this->resource;
    }

    /**
     * @param null|Resource $resource
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

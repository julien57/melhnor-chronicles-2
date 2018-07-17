<?php

namespace App\Service\Market;

use App\Entity\KingdomResource;
use App\Entity\Market;
use App\Entity\Player;
use App\Model\SaleResourceDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SaleResourceManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Verify if resource is available and quantity is sufficient
     *
     * @param SaleResourceDTO $saleResourceDTO
     *
     * @return KingdomResource|null
     */
    public function isResourceAvailableToSale(SaleResourceDTO $saleResourceDTO, Player $player): ?KingdomResource
    {
        $kingdom = $player->getKingdom();

        $quantityInKingdom = $kingdom->getKingdomResource($saleResourceDTO->getResource());

        if ($quantityInKingdom->getQuantity() < $saleResourceDTO->getQuantity()) {
            return null;
        }

        return $quantityInKingdom;
    }

    /**
     * @param KingdomResource $kingdomResource
     * @param SaleResourceDTO $saleResourceDTO
     */
    public function processingSaleResource(KingdomResource $kingdomResource, SaleResourceDTO $saleResourceDTO): void
    {
        $remainingQuantity = $kingdomResource->getQuantity() - $saleResourceDTO->getQuantity();
        $kingdomResource->setQuantity($remainingQuantity);

        $unitPrice = $kingdomResource->getResource()->getPrice();
        $sellingPrice = $unitPrice * $saleResourceDTO->getQuantity();

        $resourceForSale = Market::saleResource($saleResourceDTO, $sellingPrice, $kingdomResource);

        $this->em->persist($resourceForSale);
        $this->em->flush();
    }
}

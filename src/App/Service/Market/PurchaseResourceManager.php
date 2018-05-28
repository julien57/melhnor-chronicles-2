<?php

namespace App\Service\Market;

use App\Entity\Kingdom;
use App\Entity\KingdomResource;
use App\Entity\Market;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;

class PurchaseResourceManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Market $resourcePurchased
     *
     * @return bool
     */
    public function canPlayerBuyResource(Market $resourcePurchased, Player $buyer): bool
    {
        /** @var Kingdom $kingdom */
        $kingdom = $buyer->getKingdom();

        if ($kingdom->getGold() < $resourcePurchased->getPrice()) {
            return false;
        }

        return true;
    }

    /**
     * @param Market $resourcePurchased
     */
    public function processTransaction(Market $resourcePurchased, Player $buyer): void
    {
        /** @var Kingdom $kingdom */
        $kingdom = $buyer->getKingdom();
        $resourceSold = $resourcePurchased->getKingdomResource()->getResource();

        // THE BUYER
        // Décrease Gold
        $remainingGold = $kingdom->getGold() - $resourcePurchased->getPrice();
        $kingdom->setGold($remainingGold);

        $kingdomResources = $this->em->getRepository(KingdomResource::class)->findByKingdom($kingdom);
        $existingResources = [];
        // If resource no exist in Kingdom, add at array. Else modify quantity in KingdomResource
        /** @var KingdomResource $kingdomResource */
        foreach ($kingdomResources as $kingdomResource) {
            $existingResources[] = $kingdomResource->getResource()->getId();
            if ($kingdomResource->getResource() !== $resourceSold) {
                continue;
            }

            if ($kingdomResource->getResource() === $resourceSold) {
                $addQuantityResource = $kingdomResource->getQuantity() + $resourcePurchased->getQuantity();
                $kingdomResource->setQuantity($addQuantityResource);
            }
        }
        // If idResource no exist in Kingdom, create a new KingdomResource
        if (!in_array($resourceSold->getId(), $existingResources)) {
            $kingdomResource = new KingdomResource($kingdom, $resourceSold, $resourcePurchased->getQuantity());
            $this->em->persist($kingdomResource);
        }

        // THE SELLER
        $kingdomSeller = $resourcePurchased->getKingdomResource()->getKingdom();
        $goldKingdomSeller = $kingdomSeller->getGold();

        // Increase Gold
        $totalGold = $goldKingdomSeller + $resourcePurchased->getPrice();
        $kingdomSeller->setGold($totalGold);

        $this->em->flush();
    }
}

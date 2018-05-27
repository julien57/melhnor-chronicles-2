<?php

namespace App\Service\Market;

use App\Entity\Kingdom;
use App\Entity\KingdomResource;
use App\Entity\Market;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BuyResourceManager
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
     * @param Market $resourcePurchased
     * @return bool
     */
    public function isPossibleToBuy(Market $resourcePurchased): bool
    {
        /** @var Kingdom $kingdom */
        $kingdom = $this->tokenStorage->getToken()->getUser()->getKingdom();

        if ($kingdom->getGold() < $resourcePurchased->getPrice()) {
            return false;
        }
        return true;
    }

    /**
     * @param Market $resourcePurchased
     */
    public function processingBuyResource(Market $resourcePurchased): void
    {
        $user = $this->tokenStorage->getToken()->getUser();
        /** @var Kingdom $kingdom */
        $kingdom = $user->getKingdom();
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
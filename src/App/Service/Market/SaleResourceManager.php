<?php

namespace App\Service\Market;

use App\Entity\KingdomResource;
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
     * @return KingdomResource|bool
     */
    public function isResourceAvailableToSale(SaleResourceDTO $saleResourceDTO)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $kingdom = $user->getKingdom();

        $kingdomResources = $this->em->getRepository(KingdomResource::class)->findByKingdom($kingdom);
        /** @var KingdomResource $kingdomResource */
        foreach ($kingdomResources as $kingdomResource) {

            if ($kingdomResource->getResource() === $saleResourceDTO->getResource()) {

                if ($kingdomResource->getQuantity() < $saleResourceDTO->getQuantity()) {
                    return false;
                }

                return $kingdomResource;
            }
        }
        return false;
    }

    public function processingSaleResource(KingdomResource $kingdomResource, SaleResourceDTO $saleResourceDTO)
    {

    }
}
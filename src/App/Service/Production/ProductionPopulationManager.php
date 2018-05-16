<?php

namespace App\Service\Production;

use App\Entity\KingdomResource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductionPopulationManager
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

    public function addPopulation()
    {
        $kingdom = $this->tokenStorage->getToken()->getUser()->getKingdom();
        $population = $kingdom->getPopulation();
        $kingdomResources = $this->em->getRepository(KingdomResource::class)->findByKingdom($kingdom);

        /** @var KingdomResource $kingdomResource */
        foreach ($kingdomResources as $kingdomResource) {

            /** @var Resource $resource */
            $resource = $kingdomResource->getResource();

            if ($resource->isFood()) {

                if ($kingdomResource->getQuantity() > $population) {

                    var_dump($resource);
                }
            }
        }
        die();
    }
}
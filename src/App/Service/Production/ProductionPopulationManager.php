<?php

namespace App\Service\Production;

use App\Entity\Kingdom;
use App\Entity\KingdomResource;
use App\Entity\Player;
use App\Entity\Resource;
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

    private $resourceConsumed = [];

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return array
     */
    public function addPopulation(): array
    {
        /** @var Player $kingdom */
        $user = $this->tokenStorage->getToken()->getUser();
        $kingdom = $user->getKingdom();
        $population = $kingdom->getPopulation();
        $kingdomResources = $this->em->getRepository(KingdomResource::class)->findByKingdom($kingdom);

        $addPopulation = null;
        /** @var KingdomResource $kingdomResource */
        foreach ($kingdomResources as $kingdomResource) {
            /** @var resource $resource */
            $resource = $kingdomResource->getResource();

            if ($resource->isFood()) {
                if ($resource->getId() === 7 ||
                    $resource->getId() === 8 ||
                    $resource->getId() === 9 ||
                    $resource->getId() === 10
                ) {
                    $populationWon = $this->PopulationUseLuxury($kingdomResource);
                    $addPopulation += $populationWon;
                } elseif ($kingdomResource->getQuantity() > $population) {
                    $populationWon = $this->populationUseFood($kingdomResource, $population);
                    $addPopulation += $populationWon;
                } else {
                    $populationWon = $this->overcrowdingwithResource($kingdomResource);
                    $addPopulation += $populationWon;
                }
            }
        }
        if ($addPopulation > 600) {
            $addPopulation = 600;
        }
        $populationTotal = $population += $addPopulation;
        $kingdom->setPopulation($populationTotal);

        $this->resourceConsumed['population'] = $addPopulation;

        $actionPoints = $user->getActionPoints();
        $remainingPoints = $actionPoints - 10;
        $user->setActionPoints($remainingPoints);

        $this->em->flush();

        return $this->resourceConsumed;
    }

    /**
     * @param KingdomResource $kingdomResource
     * @param $population
     *
     * @return int
     */
    private function populationUseFood(KingdomResource $kingdomResource, $population): int
    {
        $populationUseResource = $this->randomResultPopulation(
            ($population * 80) / 100,
            ($population * 90) / 100
        );

        $remainingResource = $kingdomResource->getQuantity() - $populationUseResource;
        $kingdomResource->setQuantity($remainingResource);
        $this->resourceConsumed['consumed'][$kingdomResource->getResource()->getName()] = $populationUseResource;

        $populationWon = intval(($populationUseResource * 1) / 130);

        return $populationWon;
    }

    /**
     * @param KingdomResource $kingdomResource
     *
     * @return int
     */
    private function PopulationUseLuxury(KingdomResource $kingdomResource): int
    {
        $populationUseResource = $this->randomResultPopulation(
            ($kingdomResource->getQuantity() * 5) / 100,
            ($kingdomResource->getQuantity() * 20) / 100
        );

        $remainingResource = $kingdomResource->getQuantity() - $populationUseResource;

        $kingdomResource->setQuantity($remainingResource);

        $this->resourceConsumed['consumed'][$kingdomResource->getResource()->getName()] = $populationUseResource;

        $populationWon = intval(($populationUseResource * 3) / 130);

        return $populationWon;
    }

    /**
     * @param KingdomResource $kingdomResource
     *
     * @return int
     */
    private function overcrowdingwithResource(KingdomResource $kingdomResource): int
    {
        $populationUseResource = $this->randomResultPopulation(
            ($kingdomResource->getQuantity() * 60) / 100,
            ($kingdomResource->getQuantity() * 80) / 100
        );

        $remainingResource = $kingdomResource->getQuantity() - $populationUseResource;

        $kingdomResource->setQuantity($remainingResource);

        $this->resourceConsumed['consumed'][$kingdomResource->getResource()->getName()] = $populationUseResource;

        $populationWon = intval(($populationUseResource * 5) / 100);

        return $populationWon;
    }

    /**
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    private function randomResultPopulation(int $min, int $max): int
    {
        return rand($min, $max);
    }
}

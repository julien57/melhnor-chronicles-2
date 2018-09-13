<?php

namespace App\Service\Production;

use App\Entity\BuildingResource;
use App\Entity\Kingdom;
use App\Entity\KingdomBuilding;
use App\Entity\KingdomResource;
use App\Entity\Player;
use App\Entity\Resource;
use Doctrine\ORM\EntityManagerInterface;

class ProductionResourcesManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    // Resources Required for Production
    private $buildingRequireResources = [];

    // Production Result, contain resurces used and consumed
    private $resultProduction = [];

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Player $player
     *
     * @return array
     */
    public function processProduction(Player $player): array
    {
        $kingdom = $player->getKingdom();

        /** @var KingdomBuilding $kingdomBuilding */
        foreach ($kingdom->getKingdomBuildings() as $kingdomBuilding) {
            $buildingsResources = $this->em->getRepository(BuildingResource::class)->getBuildingsForResources($kingdomBuilding->getBuilding());

            /** @var BuildingResource $buildingResource */
            foreach ($buildingsResources as $buildingResource) {
                if ($buildingResource->isRequired()) {
                    $this->requiredInProduction($kingdom, $buildingResource, $kingdomBuilding);
                }
            }

            foreach ($buildingsResources as $buildingResource) {
                if ($buildingResource->isProduction()) {
                    $this->producedInProduction($kingdom, $buildingResource);
                }
            }
        }

        $this->em->flush();

        return $this->resultProduction;
    }

    /**
     * @param Kingdom          $kingdom
     * @param BuildingResource $buildingResource
     */
    private function producedInProduction(Kingdom $kingdom, BuildingResource $buildingResource)
    {
        $resource = $buildingResource->getResource();
        $population = $kingdom->getPopulation();

        $kingdomBuilding = $this->em->getRepository(KingdomBuilding::class)->findByKingdomAndBuilding($kingdom, $buildingResource->getBuilding());
        $kingdomResource = $this->em->getRepository(KingdomResource::class)->getKingdomExistingResource($kingdom, $resource);

        if (array_key_exists($buildingResource->getBuilding()->getId(), $this->buildingRequireResources)) {
            $requireResources = $this->requiredForProduceInProduction($kingdom, $buildingResource, $kingdomBuilding);

            return $requireResources;
        }

        $resourceProduce = $this->randomResultProduction(
            ($population / 50) * $kingdomBuilding->getLevel(),
            ($population / 35) * $kingdomBuilding->getLevel()
        );

        if (!$kingdomResource) {
            $this->createKingdomResource($resourceProduce, $kingdom, $resource);
        } else {
            $quantity = $kingdomResource->getQuantity();
            $totalQuantity = $quantity + $resourceProduce;
            $kingdomResource->setQuantity($totalQuantity);
        }

        $this->resultProduction['produced'][$resource->getName()] = $resourceProduce;
    }

    /**
     * @param Kingdom          $kingdom
     * @param BuildingResource $buildingResource
     * @param KingdomBuilding  $kingdomBuilding
     */
    private function requiredInProduction(Kingdom $kingdom, BuildingResource $buildingResource, KingdomBuilding $kingdomBuilding): void
    {
        $resource = $buildingResource->getResource();
        $kingdomResource = $this->em->getRepository(KingdomResource::class)->getKingdomExistingResource($kingdom, $resource);
        $quantity = $kingdomResource->getQuantity();

        $resourceRequire = $this->randomResultProduction(
            ($quantity * $kingdomBuilding->getLevel()) / 20,
            ($quantity * $kingdomBuilding->getLevel()) / 15
        );

        if ($resourceRequire > $quantity) {
            $resourceRequire = $quantity;
        }

        $this->buildingRequireResources[$buildingResource->getBuilding()->getId()][$buildingResource->getResource()->getId()] = $resourceRequire;
    }

    private function requiredForProduceInProduction(Kingdom $kingdom, BuildingResource $buildingResource, KingdomBuilding $kingdomBuilding)
    {
        $resourceWithQuantity = $this->buildingRequireResources[$buildingResource->getBuilding()->getId()];
        $resourceId = key($resourceWithQuantity);
        /** @var KingdomResource $kingdomResourceRequired */
        $kingdomResourceRequired = $this->em->getRepository(KingdomResource::class)->getKingdomExistingResource($kingdom, $resourceId);
        $quantityRequired = $resourceWithQuantity[$kingdomResourceRequired->getResource()->getId()];

        $resourceProduced = $this->randomResultProduction(
            ($quantityRequired * $kingdomBuilding->getLevel()) / 150,
            ($quantityRequired * $kingdomBuilding->getLevel()) / 140
        );

        // Resource Required
        $resourceInKingdom = $kingdomResourceRequired->getQuantity() - $quantityRequired;
        $kingdomResourceRequired->setQuantity($resourceInKingdom);

        $this->resultProduction['used'][$kingdomResourceRequired->getResource()->getName()] = $quantityRequired;

        // Resource Produced
        if ($resourceProduced === 0) {
            $resourceProduced = 1;
        }

        /** @var KingdomResource $kingdomResourceProduced */
        $kingdomResourceProduced = $this->em->getRepository(KingdomResource::class)->getKingdomExistingResource($kingdom, $buildingResource->getResource());

        if (!$kingdomResourceProduced) {
            $this->createKingdomResource($resourceProduced, $kingdom, $buildingResource->getResource());

            $newResourceProduced = $this->em->getRepository(KingdomResource::class)->getKingdomExistingResource($kingdom, $buildingResource->getResource());
            $this->resultProduction['produced'][$newResourceProduced->getResource()->getName()] = $resourceProduced;
        } else {
            $resourceProducedInKingdom = $kingdomResourceProduced->getQuantity() + $resourceProduced;
            $kingdomResourceProduced->setQuantity($resourceProducedInKingdom);

            $this->resultProduction['produced'][$kingdomResourceProduced->getResource()->getName()] = $resourceProduced;
        }
    }

    /**
     * @param int      $resourceProduced
     * @param Kingdom  $kingdom
     * @param resource $resource
     */
    private function createKingdomResource(int $resourceProduced, Kingdom $kingdom, Resource $resource): void
    {
        $kingdomResource = new KingdomResource($kingdom, $resource, $resourceProduced);

        $this->em->persist($kingdomResource);
        $this->em->flush();
    }

    /**
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    private function randomResultProduction(int $min, int $max): int
    {
        return rand($min, $max);
    }
}

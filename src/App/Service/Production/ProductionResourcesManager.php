<?php

namespace App\Service\Production;

use App\Entity\BuildingResource;
use App\Entity\Kingdom;
use App\Entity\KingdomBuilding;
use App\Entity\KingdomResource;
use App\Entity\Player;
use App\Entity\Resource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductionResourcesManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    private $resourcesProduced = [];

    private $resourcesRequired = [];

    private $productionUnavailable = [];

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function processProduction()
    {
        /** @var Player $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $kingdom = $user->getKingdom();

        $kingdomBuildings = $this->em->getRepository(KingdomBuilding::class)->findByKingdom($kingdom);

        /** @var KingdomBuilding $kingdomBuilding */
        foreach ($kingdomBuildings as $kingdomBuilding) {
            $building = $kingdomBuilding->getBuilding();
            $buildingResources = $this->em->getRepository(BuildingResource::class)->findByBuilding($building);

            /** @var BuildingResource $buildingResource */
            foreach ($buildingResources as $buildingResource) {
                $resource = $buildingResource->getResource();

                // If building no produced resources is null (Archery for exemple)
                if (is_null($buildingResource)) {
                    continue;
                }

                if ($buildingResource->isRequired()) {

                    $this->resourceRequiredInProduction($building, $resource, $kingdom);
                }
            }

            foreach ($buildingResources as $buildingResource) {
                // If building no produced resources is null (Archery for exemple)
                $resource = $buildingResource->getResource();

                if (is_null($buildingResource)) {
                    continue;
                }

                if ($buildingResource->isProduction()) {
                    $this->resourceProducedInProduction($building, $resource, $kingdom);
                }
            }
        }

        $this->em->flush();

        return $this->resourcesProduced;
    }

    private function resourceRequiredInProduction($building, $resource, $kingdom)
    {
        $kingdomResource = $this->em->getRepository(KingdomResource::class)->getKingdomExistingResource($kingdom, $resource);

        if (!$kingdomResource instanceof KingdomResource) {
            $this->productionUnavailable[$building->getId()] = $building->getName();
            return;
        }

        $quantityOfResource = $kingdomResource->getQuantity();

        // Use between 30% at 75% from resource available
        $resourceUsed = $this->randomResultProduction(
            ($quantityOfResource * 30) / 100,
            ($quantityOfResource * 75) / 100
        );

        $this->resourcesRequired[$building->getId()][$resource->getId()] = $resourceUsed;
    }

    private function resourceProducedInProduction($building, $resource, $kingdom)
    {
        $population = $kingdom->getPopulation();

        $kingdomBuilding = $this->em->getRepository(KingdomBuilding::class)->findOneByBuilding($building);

        if (array_key_exists($building->getId(), $this->productionUnavailable)) {
            return;
        }

        // If resource required is available in kingdom
        if (array_key_exists($building->getId(), $this->resourcesRequired)) {

            $availableResourceRequired = $this->resourcesRequired[$building->getId()];

            $idResource = key($availableResourceRequired);

            $resourceQuantityInKingdom = $this->em->getRepository(KingdomResource::class)->getKingdomExistingResource($kingdom, $idResource);

            if (is_null($resourceQuantityInKingdom)) {
                $this->productionUnavailable[$building->getId()] = $building->getName();
                return;
            }

            $quantityResource = $availableResourceRequired[$idResource];

            // Use between 60 at 85% for this resource for the production
            $quantityUsedInKingdom = $this->randomResultProduction(
                ($quantityResource * 60) / 100,
                ($quantityResource * 85) / 100
            );

            $resultQuantityWithUsed = $resourceQuantityInKingdom->getQuantity() - $quantityUsedInKingdom;

            // If quantity > 0, register in BDD
            if ($resultQuantityWithUsed > 0) {
                $resourceQuantityInKingdom->setQuantity($resultQuantityWithUsed);

                $this->em->flush();
                // else this building no produce
            } else {
                return;
            }


            $resourceProduce = intval(($quantityUsedInKingdom * $kingdomBuilding->getLevel()) / 10);

            $this->resourcesProduced['used'][$resourceQuantityInKingdom->getResource()->getName()] = $quantityUsedInKingdom;

            $this->resourcesProduced['produced'][$resource->getName()] = $resourceProduce;

            // If building no required resource, he simply produce
        } else {
            $resourceProduce = $this->randomResultProduction(
                ($population / 20) * $kingdomBuilding->getLevel(),
                ($population / 15) * $kingdomBuilding->getLevel()
            );

            $this->resourcesProduced['produced'][$resource->getName()] = $resourceProduce;
        }

        $resourceFromKingdom = $this->em->getRepository(KingdomResource::class)->getKingdomExistingResource($kingdom, $resource);

        // Update if resource exist in kingdom
        if ($resourceFromKingdom) {
            $quantityInKingdom = $resourceFromKingdom->getQuantity();

            $quantityResult = $quantityInKingdom + $resourceProduce;

            $resourceFromKingdom->setQuantity($quantityResult);

            // Create resource if no exist
        } else {
            $kingdomResource = new KingdomResource($kingdom, $resource, $resourceProduce);

            $this->em->persist($kingdomResource);
        }
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

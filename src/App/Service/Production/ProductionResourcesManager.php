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

    private $buildingsResource = [];

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
        $population = $kingdom->getPopulation();

        $kingdomBuildings = $this->em->getRepository(KingdomBuilding::class)->findByKingdom($kingdom);

        /** @var KingdomBuilding $kingdomBuilding */
        foreach ($kingdomBuildings as $kingdomBuilding) {
            $building = $kingdomBuilding->getBuilding();
            $buildingResources = $this->em->getRepository(BuildingResource::class)->findByBuilding($building);
        }

        foreach ($this->buildingsResource as $buildingsResource) {
            foreach ($buildingsResource as $buildingResource) {
                // If building no produced resources is null (Archery for exemple)
                if (!is_null($buildingResource)) {
                    if ($buildingResource->isrequired()) {
                        $resourceExist = $this->em->getRepository(KingdomResource::class)->resourcesExistsInKingdom($kingdom, $buildingResource->getResource());

                        if ($resourceExist) {
                            $quantityOfResource = $resourceExist->getQuantity();

                            // Use between 30% at 75% from resource available
                            $resourceUsed = $this->randomResultProduction(
                                ($quantityOfResource * 30) / 100,
                                ($quantityOfResource * 75) / 100
                            );

                            $this->resourcesRequired[$buildingResource->getBuilding()->getId()][$buildingResource->getResource()->getName()] = $resourceUsed;
                        }
                    }

                    if ($buildingResource->isProduction()) {
                        $kingdomBuilding = $this->em->getRepository(KingdomBuilding::class)->findOneByBuilding($buildingResource->getBuilding());

                        // If resource required is available in kingdom
                        if (array_key_exists($buildingResource->getBuilding()->getId(), $this->resourcesRequired)) {
                            $availableResourceRequired = $this->resourcesRequired[
                                $buildingResource->getBuilding()->getId()
                            ];

                            $idResource = key($availableResourceRequired);

                            $quantityResource = $availableResourceRequired[$idResource];

                            // Use between 60 at 85% for this resource for the production
                            $quantityUsedInKingdom = $this->randomResultProduction(
                                ($quantityResource * 60) / 100,
                                ($quantityResource * 85) / 100
                            );

                            $resourceQuantityInKingdom = $this->em->getRepository(KingdomResource::class)->resourcesExistsInKingdom($kingdom, $idResource);

                            $resultQuantityWithUsed = $resourceQuantityInKingdom->getQuantity() - $quantityUsedInKingdom;

                            // If quantity > 0, register in BDD
                            if ($resultQuantityWithUsed > 0) {
                                $resourceQuantityInKingdom->setQuantity($resultQuantityWithUsed);

                                // else this building no produce
                            } else {
                                continue;
                            }

                            $resourceName = $this->em->getRepository(Resource::class)->find($idResource);

                            $resourceProduce = intval(($quantityUsedInKingdom * $kingdomBuilding->getLevel()) / 10);

                            $this->resourcesProduced['used'][$resourceName->getId()] = $quantityUsedInKingdom;

                            $this->resourcesProduced['produced'][$buildingResource->getResource()->getName()] = $resourceProduce;

                            // If building no required resource, he simply produce
                        } else {
                            $resourceProduce = $this->randomResultProduction(
                                ($population / 20) * $kingdomBuilding->getLevel(),
                                ($population / 15) * $kingdomBuilding->getLevel()
                            );

                            $this->resourcesProduced['produced'][$buildingResource->getResource()->getName()] = $resourceProduce;
                        }

                        $resource = $buildingResource->getResource();

                        $resourceFromKingdom = $this->em->getRepository(KingdomResource::class)->resourcesExistsInKingdom($kingdom, $resource);

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
                }
            }
        }

        $this->em->flush();

        return $this->resourcesProduced;
    }

    public function addPopulation()
    {
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

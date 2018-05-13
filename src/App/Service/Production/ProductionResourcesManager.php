<?php

namespace App\Service\Production;

use App\Entity\BuildingResource;
use App\Entity\KingdomBuilding;
use App\Entity\KingdomResource;
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

    private $buildingsResource = [];

    private $resourceForUse = [];

    private $resultProduction = [];

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function processProduction()
    {
        $kingdom = $this->tokenStorage->getToken()->getUser()->getKingdom();

        $population = $kingdom->getPopulation();

        $kingdomBuildings = $this->em->getRepository(KingdomBuilding::class)->findByKingdom($kingdom);

        foreach ($kingdomBuildings as $kingdomBuilding) {

            $this->buildingsResource[] = $this->em->getRepository(BuildingResource::class)->findByBuilding($kingdomBuilding->getBuilding());
        }


        foreach ($this->buildingsResource as $buildingResources) {

            foreach ($buildingResources as $buildingResource) {

                $kingdomBuilding = $this->em->getRepository(KingdomBuilding::class)->findOneByBuilding($buildingResource->getBuilding());

                if ($buildingResource->isRequired()) {

                    $resourceRequired = $buildingResource->getResource();

                    $resourcesAvailableInKingdom = $this->em->getRepository(KingdomResource::class)->findOneByResource($resourceRequired);

                    if (!is_null($resourcesAvailableInKingdom)) {

                        $this->resourceForUse[$buildingResource->getBuilding()->getId()] = $resourcesAvailableInKingdom->getQuantity();
                    }
                }

                if ($buildingResource->isProduction()) {

                    if (array_key_exists($buildingResource->getBuilding()->getId(), $this->resourceForUse)) {

                        $resourceRequiredForProduce = $this->em->getRepository(BuildingResource::class)->findOneByBuilding($buildingResource->getBuilding());

                        $availableResource = $this->resourceForUse[$buildingResource->getBuilding()->getId()];

                        // Use between 50% at 75% from resource available
                        $resourceUsed = $this->randomResultProduction(
                            ($availableResource * 50) / 100,
                            ($availableResource * 75) / 100
                        );

                        $this->resultProduction['used'][$resourceRequiredForProduce->getResource()->getId()] = $resourceUsed;

                    }

                    $resourcesProduce = $this->randomResultProduction(
                        ($population / 20) * $kingdomBuilding->getLevel(),
                        ($population / 15) * $kingdomBuilding->getLevel()
                    );

                    $resource = $buildingResource->getResource();

                    $resourceFromKingdom = $this->em->getRepository(KingdomResource::class)->resourcesExistsInKingdom($kingdom, $resource);

                    // If resource ever exist in kingdom
                    if ($resourceFromKingdom) {

                        $quantityInKingdom = $resourceFromKingdom->getQuantity();

                        $quantityResult = $quantityInKingdom + $resourcesProduce;

                        $resourceFromKingdom->setQuantity($quantityResult);

                    // Else creating a new KingdomResource
                    } else {

                        $kingdomResource = new KingdomResource($kingdom, $resource, $resourcesProduce);

                        $this->em->persist($kingdomResource);
                    }

                    $this->resultProduction['produced'][$buildingResource->getResource()->getId()] = $resourcesProduce;
                }
            }
        }
        var_dump($this->resultProduction);
        die();
    }

    /**
     * @param int $min
     * @param int $max
     * @return int
     */
    private function randomResultProduction(int $min, int $max): int
    {
        return rand($min, $max);
    }
}

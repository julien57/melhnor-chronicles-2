<?php

namespace App\Service\Leveling;

use App\Entity\Kingdom;
use App\Entity\KingdomBuilding;
use App\Entity\KingdomResource;
use App\Entity\Resource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LevelingBuildingManager
{
    /**
     * @var int
     */
    private $level;

    /**
     * @var array|null
     */
    private $building = null;

    /**
     * Array contains rules for buildings from building_leveling_rules.yml
     *
     * @var array
     */
    private $buildingsRules;

    /**
     * @var int
     */
    private $goldRequired;

    /**
     * @var int|null
     */
    private $woodRequired;

    /**
     * @var int|null
     */
    private $stoneRequired;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        $buildingsRules,
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $em
    ) {
        $this->buildingsRules = $buildingsRules;
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    /**
     * @param $kingdomBuildingsForm
     * @return bool
     */
    public function searchLevelModified($kingdomBuildingsForm): bool
    {
        /** @var KingdomBuilding $kingdomBuilding */
        foreach ($kingdomBuildingsForm as $kingdomBuilding) {
            $modifiedBuilding = $this->em->getRepository(KingdomBuilding::class)->findLevelBuildingUp(
                $kingdomBuilding->getKingdom()->getId(),
                $kingdomBuilding->getBuilding()->getId(),
                $kingdomBuilding->getLevel()
            );

            if (!is_null($modifiedBuilding)) {
                $resourcesRequired = $this->processingResourcesKingdom($modifiedBuilding);

                if (!$resourcesRequired) {
                    return false;
                }
                return true;
            }
        }

        return false;
    }

    /**
     * @param $modifiedBuilding
     *
     * @return bool
     */
    private function processingResourcesKingdom(KingdomBuilding $modifiedBuilding): bool
    {
        $this->building = $this->buildingsRules[$modifiedBuilding->getBuilding()->getId()];

        $this->setLevel($modifiedBuilding->getLevel());
        $this->requiredGoldAmount();
        $this->requiredResourcesAmount();
        /** @var Kingdom $kingdomPlayer */
        $kingdomPlayer = $this->tokenStorage->getToken()->getUser()->getKingdom();

        // Gold Process
        $goldPlayer = $kingdomPlayer->getGold();

        $goldResult = $goldPlayer - $this->goldRequired;

        if ($goldResult < 0) {
            return false;
        } else {
            $kingdomPlayer->setGold($goldResult);
        }

        // Resources process (wood & stone)
        $kingdomResources = $this->em->getRepository(KingdomResource::class)->findByKingdom($kingdomPlayer);

        /** @var KingdomResource $kingdomResource */
        foreach ($kingdomResources as $kingdomResource) {
            if ($kingdomResource->getResource()->getId() === Resource::WOOD_ID) {
                $woodResult = $kingdomResource->getQuantity() - $this->woodRequired;

                if ($woodResult < 0) {
                    return false;
                }

                $kingdomResource->setQuantity($woodResult);
            }

            if ($kingdomResource->getResource()->getId() === Resource::STONE_ID) {
                $stoneResult = $kingdomResource->getQuantity() - $this->stoneRequired;

                if ($stoneResult < 0) {
                    return false;
                }

                $kingdomResource->setQuantity($stoneResult);
            }
        }

        $this->em->flush();

        return true;
    }

    /**
     * @param int $level
     */
    private function setLevel(int $level): void
    {
        $this->level = $level;
    }

    private function requiredGoldAmount()
    {
        $nbGold = ($this->level * $this->level) * $this->building['gold'];

        $this->goldRequired += $nbGold;
    }

    private function requiredResourcesAmount()
    {
        $nbWood = ($this->level * $this->level) * $this->building['resources']['24']['quantity'];

        $this->woodRequired += $nbWood;

        if (array_key_exists(Resource::STONE_ID, $this->building['resources'])) {

            $nbStone = ($this->level * $this->level) * $this->building['resources']['23']['quantity'];
            $this->stoneRequired += $nbStone;
        }
    }
}

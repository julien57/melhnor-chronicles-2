<?php

namespace App\Service\Leveling;

use App\Entity\KingdomBuilding;
use App\Entity\KingdomResource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LevelingBuildingManager
{
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
     * @var SessionInterface
     */
    private $session;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        $buildingsRules,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session,
        EntityManagerInterface $em
    ) {
        $this->buildingsRules = $buildingsRules;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->em = $em;
    }

    /**
     * @param $modifiedBuilding
     *
     * @return bool
     */
    public function processingResourcesKingdom(KingdomBuilding $modifiedBuilding)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $building = $this->buildingsRules[$modifiedBuilding->getBuilding()->getId()];
        $level = $modifiedBuilding->getLevel();

        $this->calculateRequiredGoldAmount($building, $level);
        $this->calculateRequiredResourcesAmount($building, $level);

        $kingdom = $user->getKingdom();

        // Gold Process
        $goldPlayer = $kingdom->getGold();

        $goldResult = $goldPlayer - $this->goldRequired;

        if ($goldResult < 0) {
            return false;
        } else {
            $user->getKingdom()->setGold($goldResult);
        }

        // Process leveling (wood & stone)
        $kingdomResources = $this->em->getRepository(KingdomResource::class)->findByKingdom($kingdom);
        /** @var KingdomResource $kingdomResource */
        foreach ($kingdomResources as $kingdomResource) {
            if ($kingdomResource->getResource()->getId() === 24) {
                $woodResult = $kingdomResource->getQuantity() - $this->woodRequired;

                if ($woodResult < 0) {
                    return false;
                }
                $kingdomResource->setQuantity($woodResult);
            }

            if ($kingdomResource->getResource()->getId() === 23) {
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
     * @return int|null
     */
    public function getWoodRequired(): ?int
    {
        return $this->woodRequired;
    }

    /**
     * @param int|null $woodRequired
     */
    public function setWoodRequired(?int $woodRequired): void
    {
        $this->woodRequired = $woodRequired;
    }

    /**
     * @return int|null
     */
    public function getStoneRequired(): ?int
    {
        return $this->stoneRequired;
    }

    /**
     * @param int|null $stoneRequired
     */
    public function setStoneRequired(?int $stoneRequired): void
    {
        $this->stoneRequired = $stoneRequired;
    }

    private function calculateRequiredGoldAmount(array $building, int $level): void
    {
        $nbGold = ($level * $level) * $building['gold'];

        $this->goldRequired += $nbGold;
    }

    private function calculateRequiredResourcesAmount(array $building, int $level): void
    {
        $nbWood = ($level * $level) * $building['resources']['24']['quantity'];

        $this->woodRequired += $nbWood;

        if (array_key_exists(23, $building['resources'])) {
            $nbStone = ($level * $level) * $building['resources']['23']['quantity'];

            $this->stoneRequired += $nbStone;
        }
    }
}

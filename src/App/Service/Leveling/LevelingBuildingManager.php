<?php

namespace App\Service\Leveling;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

    private function searchBuilding(int $id)
    {
        $this->building = $this->buildingsRules[$id];
    }

    private function goldRequired()
    {
        $nbGold = ($this->level * $this->level) * $this->building['gold'];

        $this->goldRequired += $nbGold;

        return $this->goldRequired;
    }

    private function resourceRequired()
    {
        $nbWood = ($this->level * $this->level) * $this->building['resources']['wood']['quantity'];

        $this->woodRequired += $nbWood;

        if (array_key_exists('stone', $this->building['resources'])) {
            $this->stoneRequired();
        }

        return $this->woodRequired;
    }

    private function stoneRequired()
    {
        $nbStone = ($this->level * $this->level) * $this->building['resources']['stone']['quantity'];

        $this->stoneRequired += $nbStone;

        return $this->stoneRequired;
    }

    public function processingResourcesKingdom($kingdomBuildingsForm, $resourcesPlayer)
    {
        foreach ($kingdomBuildingsForm as $kingdomBuilding) {
            $this->searchBuilding($kingdomBuilding->getBuilding()->getId());
            $this->setLevel($kingdomBuilding->getLevel());

            // Addition the gold necessary for level up
            $this->goldRequired();

            // Addition the resources (wood & stone) necessary
            $this->resourceRequired();
        }

        // Gold Process
        $goldPlayer = $this->tokenStorage->getToken()->getUser()->getKingdom()->getGold();

        $goldResult = $goldPlayer - $this->goldRequired;

        if ($goldResult < 0) {
            return;
        } else {
            $this->tokenStorage->getToken()->getUser()->getKingdom()->setGold($goldResult);
        }

        // Resources process (wood & stone)
        foreach ($resourcesPlayer as $resourcePlayer) {
            if ($resourcePlayer->getResource()->getId() === 24) {
                $woodResult = $resourcePlayer->getQuantity() - $this->woodRequired;

                if ($woodResult < 0) {
                    return;
                }
                $resourcePlayer->setQuantity($woodResult);
            }

            if ($resourcePlayer->getResource()->getId() === 23) {
                $stoneResult = $resourcePlayer->getQuantity() - $this->stoneRequired;

                if ($stoneResult < 0) {
                    return;
                }
                $resourcePlayer->setQuantity($stoneResult);
            }
        }

        $this->em->flush();

        return true;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * @return array|null
     */
    public function getBuilding(): ?array
    {
        return $this->building;
    }

    /**
     * @param array|null $building
     */
    public function setBuilding(?array $building): void
    {
        $this->building = $building;
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
}

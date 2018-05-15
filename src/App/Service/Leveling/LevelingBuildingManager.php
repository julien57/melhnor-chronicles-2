<?php

namespace App\Service\Leveling;

use App\Entity\KingdomResource;
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

    /**
     * @param $modifiedBuilding
     *
     * @return bool
     */
    public function processingResourcesKingdom($modifiedBuilding)
    {
        $this->building = $this->buildingsRules[$modifiedBuilding->getBuilding()->getId()];

        $this->setLevel($modifiedBuilding->getLevel());
        $this->requiredGoldAmount();
        $this->requiredResourcesAmount();

        $kingdomPlayer = $this->tokenStorage->getToken()->getUser()->getKingdom();

        // Gold Process
        $goldPlayer = $kingdomPlayer->getGold();

        $goldResult = $goldPlayer - $this->goldRequired;

        if ($goldResult < 0) {
            return false;
        } else {
            $this->tokenStorage->getToken()->getUser()->getKingdom()->setGold($goldResult);
        }

        // Resources process (wood & stone)
        $kingdomResources = $this->em->getRepository(KingdomResource::class)->findByKingdom($kingdomPlayer);

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

    private function requiredGoldAmount()
    {
        $nbGold = ($this->level * $this->level) * $this->building['gold'];

        $this->goldRequired += $nbGold;
    }

    private function requiredResourcesAmount()
    {
        $nbWood = ($this->level * $this->level) * $this->building['resources']['24']['quantity'];

        $this->woodRequired += $nbWood;

        if (array_key_exists('23', $this->building['resources'])) {
            $nbStone = ($this->level * $this->level) * $this->building['resources']['23']['quantity'];

            $this->stoneRequired += $nbStone;
        }
    }
}

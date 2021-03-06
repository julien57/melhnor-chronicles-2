<?php

namespace App\Entity;

use App\Model\BuildBuildingDTO;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="kingdom_building")
 * @ORM\Entity(repositoryClass="App\Repository\KingdomBuildingRepository")
 */
class KingdomBuilding
{
    const LEVEL_BUILDING_NOT_BUILD = 0;

    const LEVEL_BUILDING_START_BUILD = 1;

    const BUILDING_RECRUITMENT_SOLDIER = 21;

    const BUILDING_RECRUITMENT_ARCHERY = 22;

    const BUILDING_RECRUITMENT_STABLE = 15;

    const BUILDING_RECRUITMENT_BOAT = 23;

    const MAX_UNITY_START = 10;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level = self::LEVEL_BUILDING_NOT_BUILD;

    /**
     * @var int
     *
     * @ORM\Column(name="max_unity_army", type="integer")
     */
    private $maxUnityArmy = self::MAX_UNITY_START;

    /**
     * @var Kingdom
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Kingdom", inversedBy="kingdomBuildings")
     */
    private $kingdom;

    /**
     * @var Building
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Building", inversedBy="kingdomBuildings")
     */
    private $building;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return Kingdom
     */
    public function getKingdom(): Kingdom
    {
        return $this->kingdom;
    }

    /**
     * @param Kingdom $kingdom
     */
    public function setKingdom(Kingdom $kingdom): void
    {
        $this->kingdom = $kingdom;
    }

    /**
     * @return Building
     */
    public function getBuilding(): Building
    {
        return $this->building;
    }

    /**
     * @param Building $building
     */
    public function setBuilding(Building $building): void
    {
        $this->building = $building;
    }

    /**
     * @return int
     */
    public function getMaxUnityArmy(): int
    {
        return $this->maxUnityArmy;
    }

    /**
     * @param int $maxUnityArmy
     */
    public function setMaxUnityArmy(int $maxUnityArmy): void
    {
        $this->maxUnityArmy = $maxUnityArmy;
    }

    public static function initKingdomBuilding(BuildBuildingDTO $buildBuildingDTO, Kingdom $kingdom)
    {
        $kingdomBuilding = new self();

        $kingdomBuilding->kingdom = $kingdom;
        $kingdomBuilding->level = self::LEVEL_BUILDING_START_BUILD;
        $kingdomBuilding->building = $buildBuildingDTO->getBuilding();

        return $kingdomBuilding;
    }
}

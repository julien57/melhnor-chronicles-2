<?php

namespace App\Model;

use App\Entity\Building;
use App\Entity\Kingdom;
use App\Entity\KingdomBuilding;

class LevelBuildingDTO
{
    /**
     * @var Kingdom|null
     */
    private $kingdom;

    /**
     * @var KingdomBuilding|null
     */
    private $kingdomBuildings;

    /**
     * @var int|null
     */
    private $level;

    /**
     * @var Building|null
     */
    private $building;

    /**
     * @return Kingdom|null
     */
    public function getKingdom(): ?Kingdom
    {
        return $this->kingdom;
    }

    /**
     * @param Kingdom|null $kingdom
     */
    public function setKingdom(?Kingdom $kingdom): void
    {
        $this->kingdom = $kingdom;
    }

    /**
     * @return KingdomBuilding|null
     */
    public function getKingdomBuildings(): ?KingdomBuilding
    {
        return $this->kingdomBuildings;
    }

    /**
     * @param KingdomBuilding|null $kingdomBuildings
     */
    public function setKingdomBuildings(?KingdomBuilding $kingdomBuildings): void
    {
        $this->kingdomBuildings = $kingdomBuildings;
    }

    /**
     * @return int|null
     */
    public function getLevel(): ?int
    {
        return $this->level;
    }

    /**
     * @param int|null $level
     */
    public function setLevel(?int $level): void
    {
        $this->level = $level;
    }

    /**
     * @return Building|null
     */
    public function getBuilding(): ?Building
    {
        return $this->building;
    }

    /**
     * @param Building|null $building
     */
    public function setBuilding(?Building $building): void
    {
        $this->building = $building;
    }
}

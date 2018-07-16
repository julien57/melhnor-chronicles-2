<?php

namespace App\Model;

use App\Entity\Building;
use App\Entity\Kingdom;
use Symfony\Component\Validator\Constraints as Assert;

class BuildBuildingDTO
{
    /**
     * @var Kingdom|null
     */
    private $kingdom;

    /**
     * @var Building|null
     */
    private $building;

    /**
     * @var int|null
     * @Assert\Type("integer", message="buil_building.level.not_int")
     */
    private $level;

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
}

<?php

namespace App\Model;

use App\Entity\Army;

class ArmyRecruitmentDTO
{
    /**
     * @var Army
     */
    private $army;

    /**
     * @return Army
     */
    public function getArmy(): Army
    {
        return $this->army;
    }

    /**
     * @param Army $army
     */
    public function setArmy(Army $army): void
    {
        $this->army = $army;
    }
}

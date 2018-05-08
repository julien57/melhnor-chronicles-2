<?php

namespace App\Service\Leveling;

use Symfony\Component\Yaml\Yaml;

class ModifyLevelBuilding
{
    /**
     * @var int
     */
    private $level;

    /**
     * @var Yaml
     */
    private $config;

    /**
     * @var null
     */
    private $building = null;

    private $loader;

    public function __construct($loader, int $level)
    {
        $this->level = $level;
        $this->loader = $loader;
        var_dump($this->loader); die();
    }

    public function searchBuilding(int $id) {

        switch ($id) {

            case 1:
                $this->building = $this->config['building_leveling_rules']['hunting_lodge'];
                break;
            default:
                $this->building = null;
        }
    }

    public function goldRequired()
    {
        $nbGold = ($this->level * $this->level) * $this->building['resources']['wood']['quantity'];

        return $nbGold;
    }

    public function woodRequired()
    {
        $nbWood = ($this->level * $this->level) * $this->building['resources']['wood']['quantity'];

        return $nbWood;
    }
}

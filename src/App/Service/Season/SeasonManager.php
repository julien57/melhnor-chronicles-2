<?php

namespace App\Service\Season;

use App\Entity\Season;
use Doctrine\ORM\EntityManagerInterface;

class SeasonManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return string
     */
    public function displayDate(): string
    {
        $dateGame = $this->em->getRepository(Season::class)->findOneBy(['id' => 1]);

        switch($dateGame->getMonth())
        {
            case 1 : $season = 'Saison des Fleurs'; break;
            case 2 : $season = 'Saison Chaude'; break;
            case 3 : $season = 'Saison des Feuilles'; break;
            case 4 : $season = 'Saison Froide'; break;
        }

        $eme = ($dateGame->getDay() == 1) ? 'er' : 'ème';
        
        return $dateGame->getDay().$eme.' jour de la '.$season.', an '.$dateGame->getYear();
    }
}
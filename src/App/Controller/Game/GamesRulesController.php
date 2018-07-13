<?php

namespace App\Controller\Game;

use App\Entity\Building;
use App\Entity\BuildingResource;
use App\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GamesRulesController extends Controller
{
    /**
     * @return Response
     *
     * @Route("/regles-du-jeu", name="game_rules")
     */
    public function rulesAction(EntityManagerInterface $em): Response
    {
        $buildings = $em->getRepository(Building::class)->findAll();
        $buildingsResources = $em->getRepository(BuildingResource::class)->getBuildingsWithResources();
        $regions = $em->getRepository(Region::class)->findAll();

        return $this->render('Game/game_rules.html.twig', [
            'buildings' => $buildings,
            'buildingsResources' => $buildingsResources,
            'regions' => $regions,
        ]);
    }
}

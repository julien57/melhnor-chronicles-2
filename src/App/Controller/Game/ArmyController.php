<?php

namespace App\Controller\Game;

use App\Entity\Army;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArmyController extends Controller
{
    /**
     * @return Response
     *
     * @Route("/armee", name="game_army")
     */
    public function armyAction(): Response
    {
        $kingdom = $this->getUser()->getKingdom();

        return $this->render('Game/army.html.twig', ['kingdom' => $kingdom]);
    }
}
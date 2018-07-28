<?php

namespace App\Controller\Game;

use App\Entity\Player;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ThroneController extends Controller
{
    /**
     * @return Response
     *
     * @Route("/salle-du-trone", name="game_throne")
     * @Security("has_role('ROLE_PLAYER')")
     */
    public function throneAction(): Response
    {
        return $this->render('Game/throne.html.twig');
    }
}

<?php

namespace App\Controller\Game;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TroneController extends Controller
{
    /**
     * @Route("/salle-du-trone", name="trone")
     */
    public function troneAction(): Response
    {
        return $this->render('Game/trone.html.twig');
    }
}

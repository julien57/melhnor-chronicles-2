<?php

namespace App\Controller\Game;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GameController extends Controller
{
    /**
     * @Route("/", name="game_index")
     */
    public function indexAction(): Response
    {
        return $this->render('Game/index.html.twig');
    }
}

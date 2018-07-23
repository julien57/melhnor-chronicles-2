<?php

namespace App\Controller\Game;

use App\Entity\Player;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RankingController extends Controller
{
    /**
     * @Route("/classement", name="game_ranking")
     */
    public function indexAction(): Response
    {
        $players = $this->getDoctrine()->getRepository(Player::class)->playersByPopulation();

        return $this->render('Game/ranking.html.twig', ['players' => $players]);
    }
}
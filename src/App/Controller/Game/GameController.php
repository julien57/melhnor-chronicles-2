<?php

namespace App\Controller\Game;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GameController extends Controller
{
    /**
     * @Route("/", name="game_index")
     */
    public function indexAction(EntityManagerInterface $em): Response
    {
        $countPlayersConnected = $em->getRepository(Player::class)->countPlayersConnected();
        $totalPlayers = $em->getRepository(Player::class)->countAllPlayers();

        return $this->render('Game/index.html.twig', [
            'countPlayersConnected' => $countPlayersConnected,
            'totalPlayers' => $totalPlayers
        ]);
    }
}

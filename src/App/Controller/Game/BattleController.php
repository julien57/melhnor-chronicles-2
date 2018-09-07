<?php

namespace App\Controller\Game;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class BattleController extends Controller
{
    /**
     * @Route("/bataille", name="game_battle")
     */
    public function battleAction(EntityManagerInterface $em)
    {
        $players = $em->getRepository(Player::class)->findAll();

        return $this->render('Game/battle.html.twig', ['players' => $players]);
    }
}
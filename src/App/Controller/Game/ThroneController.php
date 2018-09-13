<?php

namespace App\Controller\Game;

use App\Entity\KingdomArmy;
use App\Entity\KingdomEvent;
use Doctrine\ORM\EntityManagerInterface;
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
    public function throneAction(EntityManagerInterface $em): Response
    {
        $kingdom = $this->getUser()->getKingdom();
        $kingdomArmys = $em->getRepository(KingdomArmy::class)->findByKingdom($kingdom);
        $kingdomEvents = $em->getRepository(KingdomEvent::class)->findBy(['kingdom' => $kingdom]);

        return $this->render('Game/throne.html.twig', [
            'kingdomArmys' => $kingdomArmys,
            'kingdomEvents' => $kingdomEvents,
        ]);
    }
}

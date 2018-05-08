<?php

namespace App\Controller\Game;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GameController extends Controller
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
     * @Route("/armee", name="army")
     */
    public function armyAction()
    {
        $kingdomBuildings = $this->getUser()->getKingdom()->getKingdomBuildings();

        return $this->render('Game/army.html.twig', [
            'kingdomBuildings' => $kingdomBuildings,
        ]);
    }
}

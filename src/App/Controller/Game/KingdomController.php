<?php

namespace App\Controller\Game;

use App\Entity\KingdomBuilding;
use App\Entity\KingdomResource;
use App\Form\KingdomType;
use App\Service\Leveling\LevelingBuildingManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class KingdomController extends Controller
{
    /**
     * @Route("/royaume", name="kingdom")
     */
    public function kingdomAction(Request $request, EntityManagerInterface $em, LevelingBuildingManager $levelingBuildingManager)
    {
        $kingdom = $this->getUser()->getKingdom();

        $formBuilding = $this->createForm(KingdomType::class, $kingdom)->handleRequest($request);
        if ($formBuilding->isSubmitted() && $formBuilding->isValid()) {

            $levelingBuildingManager->searchLevelModified($formBuilding->getData()->getKingdomBuildings());
        }

        $kingdomResources = $em->getRepository(KingdomResource::class)->findBy(['kingdom' => $kingdom]);

        return $this->render('Game/kingdom.html.twig', [
            'formBuilding' => $formBuilding->createView(),
            'kingdomResources' => $kingdomResources,
        ]);
    }
}

<?php

namespace App\Controller\Game;

use App\Entity\KingdomBuilding;
use App\Entity\KingdomResource;
use App\Form\KingdomType;
use App\Service\Leveling\LevelingBuildingManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class KingdomController extends Controller
{
    /**
     * @param Request                 $request
     * @param EntityManagerInterface  $em
     * @param LevelingBuildingManager $levelingBuildingManager
     *
     * @return Response
     *
     * @Route("/royaume", name="kingdom")
     */
    public function kingdomAction(Request $request, EntityManagerInterface $em, LevelingBuildingManager $levelingBuildingManager): Response
    {
        $kingdom = $this->getUser()->getKingdom();
        $formBuilding = $this->createForm(KingdomType::class, $kingdom)->handleRequest($request);

        if ($formBuilding->isSubmitted() && $formBuilding->isValid()) {
            $levelingBuildingManager->searchLevelModified($formBuilding->getData()->getKingdomBuildings());
        }

        $kingdomResources = $em->getRepository(KingdomResource::class)->findByKingdom($kingdom);
        $kingdomBuildings = $em->getRepository(KingdomBuilding::class)->findBy(['kingdom' => $kingdom]);

        return $this->render('Game/kingdom.html.twig', [
            'kingdomResources' => $kingdomResources,
            'kingdomBuildings' => $kingdomBuildings,
            'formBuilding' => $formBuilding->createView(),
        ]);
    }
}

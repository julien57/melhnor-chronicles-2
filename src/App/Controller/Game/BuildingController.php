<?php

namespace App\Controller\Game;

use App\Entity\KingdomBuilding;
use App\Form\BuildBuildingType;
use App\Model\BuildBuildingDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BuildingController extends Controller
{
    /**
     * @Route("/construction-batiment", name="game_build_building")
     */
    public function buildBuildingAction(Request $request, EntityManagerInterface $em): Response
    {
        $buildBuildingDTO = new BuildBuildingDTO();
        $buildBuildingForm = $this->createForm(BuildBuildingType::class, $buildBuildingDTO);
        $buildBuildingForm->handleRequest($request);

        if ($buildBuildingForm->isSubmitted() && $buildBuildingForm->isValid()) {
            $kingdom = $this->getUser()->getKingdom();
            $kingdomBuilding = KingdomBuilding::initKingdomBuilding($buildBuildingDTO, $kingdom);

            $em->persist($kingdomBuilding);
            $em->flush();

            $this->addFlash('notice', 'Bâtiment construit !');

            return $this->redirectToRoute('game_kingdom');
        }

        return $this->render('Game/build_building.html.twig', [
            'buildBuildingForm' => $buildBuildingForm->createView(),
        ]);
    }
}

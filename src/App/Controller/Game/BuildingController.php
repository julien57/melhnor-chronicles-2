<?php

namespace App\Controller\Game;

use App\Entity\Building;
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
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/construction-batiment", name="build-building")
     */
    public function buildBuilding(Request $request): Response
    {
        $buildBuilding = new BuildBuildingDTO();
        $buildBuildingForm = $this->createForm(BuildBuildingType::class, $buildBuilding);
        $buildBuildingForm->handleRequest($request);

        if ($buildBuildingForm->isValid()) {
            $building = $this->em
                ->getRepository(Building::class)
                ->find($buildBuildingForm->getData()->getBuilding())
            ;

            $kingdomBuilding = new KingdomBuilding();
            $kingdomBuilding->setKingdom($this->getUser()->getKingdom());
            $kingdomBuilding->setLevel(1);
            $kingdomBuilding->setBuilding($building);

            $this->em->persist($kingdomBuilding);
            $this->em->flush();

            $this->addFlash('notice', 'Bâtiment construit !');

            return $this->redirectToRoute('kingdom');
        }

        return $this->render('Game/build_building.html.twig', [
            'buildBuildingForm' => $buildBuildingForm->createView(),
        ]);
    }
}

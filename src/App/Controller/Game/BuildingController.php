<?php

namespace App\Controller\Game;

use App\Entity\Building;
use App\Entity\KingdomBuilding;
use App\Form\BuildBuildingType;
use App\Model\BuildBuildingDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    public function buildBuilding(): Response
    {
        $buildBuilding = new BuildBuildingDTO();
        $buildBuildingForm = $this->createForm(BuildBuildingType::class, $buildBuilding);


        return $this->render('Game/build-building.html.twig', [
            'buildBuildingForm' => $buildBuildingForm->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/construction-batiment-process", name="build-building-process")
     */
    public function processingBuildBuilding(Request $request): RedirectResponse
    {
        $building = $this->em->getRepository(Building::class)->find(
            $request->request->get('build_building')['building']);

        $kingdomBuilding = new KingdomBuilding();
        $kingdomBuilding->setKingdom($this->getUser()->getKingdom());
        $kingdomBuilding->setLevel(1);
        $kingdomBuilding->setBuilding($building);

        $this->em->persist($kingdomBuilding);
        $this->em->flush();

        $this->addFlash('notice', 'Bâtiment construit !');

        return $this->redirectToRoute('kingdom');
    }
}
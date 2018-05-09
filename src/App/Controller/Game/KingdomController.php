<?php

namespace App\Controller\Game;

use App\Entity\Building;
use App\Entity\Kingdom;
use App\Entity\KingdomBuilding;
use App\Entity\KingdomResource;
use App\Form\BuildBuildingType;
use App\Form\KingdomType;
use App\Model\BuildBuildingDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class KingdomController extends Controller
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
     * @Route("/salle-du-trone", name="trone")
     */
    public function troneAction()
    {
        return $this->render('Game/trone.html.twig');
    }

    /**
     * @Route("/royaume", name="kingdom")
     */
    public function kingdomAction(Request $request)
    {
        // Build a Building Form
        $buildBuilding = new BuildBuildingDTO();
        $buildBuildingForm = $this->createForm(BuildBuildingType::class, $buildBuilding);
        $buildBuildingForm->handleRequest($request);

        if ($buildBuildingForm->isValid()) {
            $building = $this->em->getRepository(Building::class)->find($buildBuildingForm->get('building')->getData());

            $kingdomBuilding = new KingdomBuilding();
            $kingdomBuilding->setKingdom($this->getUser()->getKingdom());
            $kingdomBuilding->setLevel(1);
            $kingdomBuilding->setBuilding($building);

            $this->em->persist($kingdomBuilding);
            $this->em->flush();

            $this->addFlash('notice', 'Bâtiment construit !');

            return $this->redirectToRoute('kingdom');
        }

        // Buildings and levels Form
        $kingdom = $this->getUser()->getKingdom();

        $formBuilding = $this->createForm(KingdomType::class, $kingdom);
        $formBuilding->handleRequest($request);

        if ($formBuilding->isValid()) {
            $modifyLevelBuilding = $this->get('leveling.modify_leveling_building');

            $resourcesPlayer = $this->em->getRepository(KingdomResource::class)->findByKingdom($kingdom);

            $kingdomBuildingsForm = $formBuilding->getData()->getKingdomBuildings();

            // Search a building with modified level
            foreach ($kingdomBuildingsForm as $kingdomBuilding) {
                $modifiedLevels = $this->em->getRepository(KingdomBuilding::class)->findLevelBuildingUp(
                    $kingdomBuilding->getKingdom()->getId(),
                    $kingdomBuilding->getBuilding()->getId(),
                    $kingdomBuilding->getLevel()
                );
                if (!empty($modifiedLevels)) {
                    $resourcesRequired = $modifyLevelBuilding->processingResourcesKingdom($modifiedLevels, $resourcesPlayer);
                }
            }

            if (is_null($resourcesRequired)) {
                $this->addFlash('notice-danger', 'Ressources manquantes !');

                return $this->redirectToRoute('kingdom');
            }

            $this->addFlash('notice', 'Niveau du bâtiment augmenté !');
            $this->redirectToRoute('kingdom');
        }

        $kingdomResources = $this->em
            ->getRepository(KingdomResource::class)
            ->findBy(['kingdom' => $kingdom])
        ;

        return $this->render('Game/kingdom.html.twig', [
            'formBuilding' => $formBuilding->createView(),
            'buildBuildingForm' => $buildBuildingForm->createView(),
            'kingdomResources' => $kingdomResources,
        ]);
    }

    /**
     * @Route("/production", name="production")
     */
    public function productionAction()
    {
    }
}

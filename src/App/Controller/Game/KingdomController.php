<?php

namespace App\Controller\Game;

use App\Entity\Building;
use App\Entity\Kingdom;
use App\Entity\KingdomBuilding;
use App\Entity\KingdomResource;
use App\Form\BuildBuildingType;
use App\Form\BuildingForm;
use App\Form\BuildingFormType;
use App\Form\IncreaseBuildingType;
use App\Form\KingdomBuildingType;
use App\Form\KingdomType;
use App\Form\LevelBuildingType;
use App\Model\BuildBuildingDTO;
use App\Model\LevelBuildingDTO;
use App\Model\SetBuildingLevelDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;

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

        // Find resources and buildings for Player
        $kingdom = $this->getUser()->getKingdom();

        $kingdomResources = $this->em
            ->getRepository(KingdomResource::class)
            ->findBy(['kingdom' => $kingdom])
        ;

        // Tests Level Buildings

        $formBuilding = $this->createForm(KingdomType::class, $kingdom);
        $formBuilding->handleRequest($request);

        if ($formBuilding->isValid()) {

            $increaseLevelBuilding = $this->container->get('melhnor.modify_level_building');
            $increaseLevelBuilding->searchBuilding(1);

            $kingdomBuildingsForm = $formBuilding->getData()->getKingdomBuildings();

            foreach ($kingdomBuildingsForm as $kingdomBuilding) {
                var_dump($kingdomBuilding->getBuilding()->getId());
            }
            die();

            $this->em->flush();


            // Créer un service qui va calculer les resources nécessaires pour le niveau demandé (S'aider du fichier YML)
            // Controler si le joueur possède les ressources nécessaires
                // Si oui : message flash + nouveau niveau bâtiment dans liste bâtiments
                // Si non : message flash disant que le joueur n'a pas assez de ressources
        }

        return $this->render('Game/kingdom.html.twig', [
            'formBuilding' => $formBuilding->createView(),
            'buildBuildingForm' => $buildBuildingForm->createView(),
            'kingdomResources' => $kingdomResources,
        ]);
    }

    /**
     * @Route("/set-level-building", name="levelBuilding")
     */
    public function levelBuildingAction(Request $request)
    {
    }
}

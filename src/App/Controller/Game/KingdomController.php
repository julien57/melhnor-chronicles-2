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
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LevelingBuildingManager
     */
    private $levelingBuildingManager;

    public function __construct(EntityManagerInterface $em, LevelingBuildingManager $levelingBuildingManager)
    {
        $this->em = $em;
        $this->levelingBuildingManager = $levelingBuildingManager;
    }

    /**
     * @Route("/royaume", name="kingdom")
     */
    public function kingdomAction(Request $request)
    {
        $kingdom = $this->getUser()->getKingdom();

        $formBuilding = $this->createForm(KingdomType::class, $kingdom);
        $formBuilding->handleRequest($request);

        if ($formBuilding->isValid()) {
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
                    $resourcesRequired = $this->levelingBuildingManager->processingResourcesKingdom($modifiedLevels, $resourcesPlayer);
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
            'kingdomResources' => $kingdomResources,
        ]);
    }
}

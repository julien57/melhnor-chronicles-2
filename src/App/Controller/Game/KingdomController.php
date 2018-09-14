<?php

namespace App\Controller\Game;

use App\Entity\KingdomBuilding;
use App\Entity\KingdomResource;
use App\Form\KingdomType;
use App\Service\Leveling\LevelingBuildingManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;

class KingdomController extends Controller
{
    /**
     * @param Request                 $request
     * @param EntityManagerInterface  $em
     * @param LevelingBuildingManager $levelingBuildingManager
     *
     * @return Response
     *
     * @Route("/royaume", name="game_kingdom")
     * @Security("has_role('ROLE_PLAYER')")
     */
    public function kingdomAction(Request $request, EntityManagerInterface $em, LevelingBuildingManager $levelingBuildingManager, TranslatorInterface $translator): Response
    {
        $kingdom = $this->getUser()->getKingdom();
        $formBuilding = $this->createForm(KingdomType::class, $kingdom)->handleRequest($request);

        if ($formBuilding->isSubmitted() && $formBuilding->isValid()) {
            $isValid = $levelingBuildingManager->searchLevelModified($formBuilding->getData()->getKingdomBuildings());

            if (!$isValid) {
                $this->addFlash(
                    'notice-danger',
                    $translator->trans('messages.service-leveling-unavailable-resource', [], 'game')
                );

                return $this->redirectToRoute('game_kingdom');
            }

            $this->addFlash(
                'notice',
                $translator->trans('messages.service-leveling-increased-level', [], 'game')
            );

            return $this->redirectToRoute('game_kingdom');
        }

        $kingdomResources = $em->getRepository(KingdomResource::class)->getResourceByisFood($kingdom);
        $kingdomBuildings = $em->getRepository(KingdomBuilding::class)->findBy(['kingdom' => $kingdom]);

        return $this->render('Game/kingdom.html.twig', [
            'kingdomResources' => $kingdomResources,
            'kingdomBuildings' => $kingdomBuildings,
            'formBuilding' => $formBuilding->createView(),
        ]);
    }
}

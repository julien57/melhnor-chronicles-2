<?php

namespace App\Controller\Game;

use App\Entity\Kingdom;
use App\Entity\KingdomBuilding;
use App\Form\BuildBuildingType;
use App\Model\BuildBuildingDTO;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class BuildingController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * @Route("/construction-batiment", name="game_building_build")
     * @Security("has_role('ROLE_PLAYER')")
     */
    public function buildAction(Request $request): Response
    {
        $buildBuildingDTO = new BuildBuildingDTO();
        $buildBuildingForm = $this->createForm(BuildBuildingType::class, $buildBuildingDTO);
        $buildBuildingForm->handleRequest($request);

        if ($buildBuildingForm->isSubmitted() && $buildBuildingForm->isValid()) {
            /** @var Kingdom $kingdom */
            $kingdom = $this->getUser()->getKingdom();
            $countKingdomBuildings = $this->em->getRepository(KingdomBuilding::class)->count(['kingdom' => $kingdom]);

            if ($countKingdomBuildings >= $kingdom->getLocationBuildings()) {
                $this->addFlash('notice-danger', $this->translator->trans('messages.lack-location', [], 'game'));

                return $this->redirectToRoute('game_kingdom');
            }

            $kingdomBuilding = KingdomBuilding::initKingdomBuilding($buildBuildingDTO, $kingdom);

            $this->em->persist($kingdomBuilding);
            $this->em->flush();

            $this->addFlash('notice', $this->translator->trans('messages.built-building', [], 'game'));

            return $this->redirectToRoute('game_kingdom');
        }

        return $this->render('Game/build_building.html.twig', [
            'buildBuildingForm' => $buildBuildingForm->createView(),
        ]);
    }

    /**
     * @Route("/destruction-batiment/{id}", name="game_building_destroy")
     * @Security("has_role('ROLE_PLAYER')")
     */
    public function destroyAction(KingdomBuilding $id)
    {
        $kingdom = $this->getUser()->getKingdom();

        if ($kingdom !== $kingdomBuilding->getKingdom()) {
            $this->addFlash('notice-danger', $this->translator->trans('messages.not-destroy-building', [], 'game'));

            return $this->redirectToRoute('game_kingdom');
        }

        $this->em->remove($kingdomBuilding);
        $this->em->flush();

        $this->addFlash('notice', $this->translator->trans('messages.destroyed-building', [], 'game'));

        return $this->redirectToRoute('game_kingdom');
    }
}

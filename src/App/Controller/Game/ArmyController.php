<?php

namespace App\Controller\Game;

use App\Entity\Army;
use App\Entity\Building;
use App\Entity\KingdomBuilding;
use App\Form\RecruitmentType;
use App\Model\ArmyRecruitmentDTO;
use App\Service\Recruitment\ArmyRecruitment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class ArmyController extends Controller
{
    /**
     * @return Response
     *
     * @Route("/armee", name="game_army")
     */
    public function armyAction(Request $request, ArmyRecruitment $armyRecruitment, TranslatorInterface $translator): Response
    {
        $kingdom = $this->getUser()->getKingdom();
        $army = $this->getDoctrine()->getRepository(Army::class)->findByKingdom($kingdom);
        $buildings = $this->getDoctrine()->getRepository(KingdomBuilding::class)->getBuildingsFromKingdom($kingdom);

        $armyRecruitmentDTO = new ArmyRecruitmentDTO();
        $form = $this->createForm(RecruitmentType::class, $armyRecruitmentDTO, [
            'army' => $army,
            'buildings' => $buildings
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recruitment = $armyRecruitment->recruitmentProcess($armyRecruitmentDTO, $kingdom);

            if (!$recruitment) {
                $this->addFlash(
                    'notice-danger',
                    $translator->trans('messages.recruitment-error', [], 'game')
                );
                return $this->redirectToRoute('game_army');
            }

            $this->addFlash(
                'notice',
                $translator->trans('messages.recruitment-success', [], 'game')
            );
            return $this->redirectToRoute('game_army');
        }

        return $this->render('Game/army.html.twig', [
            'army' => $army,
            'kingdom' => $kingdom,
            'buildings' => $buildings,
            'form' => $form->createView()
        ]);
    }
}
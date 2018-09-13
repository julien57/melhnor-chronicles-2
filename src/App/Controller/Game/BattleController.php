<?php

namespace App\Controller\Game;

use App\Entity\KingdomArmy;
use App\Entity\Player;
use App\Form\ArmyStrategyType;
use App\Model\ArmyStrategyDTO;
use App\Service\Battle\PlayerVsPlayerManager;
use App\Service\Event\VerifyArmyManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class BattleController extends Controller
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
     * @Route("/bataille", name="game_battle")
     */
    public function battleAction()
    {
        $players = $this->em->getRepository(Player::class)->allPlayersWithoutAdmin();

        return $this->render('Game/battle.html.twig', ['players' => $players]);
    }

    /**
     * @Route("/strategie/bataille/{id}", name="game_battle_strategy")
     */
    public function strategyBattleAction(Player $defender, Request $request, VerifyArmyManager $verifyArmyManager, PlayerVsPlayerManager $playerVsPlayerManager)
    {
        $player = $this->getUser();
        $kingdom = $player->getKingdom();

        $armyStrategyDTO = new ArmyStrategyDTO();
        $kingdomArmys = $this->em->getRepository(KingdomArmy::class)->findBy(['kingdom' => $kingdom]);
        $form = $this->createForm(ArmyStrategyType::class, $armyStrategyDTO, ['kingdomArmys' => $kingdomArmys]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($player->getActionPoints() < Player::ACTION_POINTS_FOR_BATTLE) {
                $this->addFlash(
                    'notice-danger',
                    $this->translator->trans('messages.unavailable-ap-battle', [], 'game')
                );

                return $this->redirectToRoute('game_battle_strategy', ['id' => $defender->getId()]);
            }

            $isArmyValid = $verifyArmyManager->verifyArmy($armyStrategyDTO, $kingdomArmys);
            if (!$isArmyValid) {
                $this->addFlash(
                    'notice-danger',
                    $this->translator->trans('messages.unaivalable-army', [], 'game')
                );

                return $this->redirectToRoute('game_battle_strategy', ['id' => $defender->getId()]);
            }

            $remainingActionPoints = $player->getActionPoints() - Player::ACTION_POINTS_FOR_BATTLE;
            $player->setActionPoints($remainingActionPoints);
            $this->em->flush();

            $historicBattle = $playerVsPlayerManager->battle($kingdom, $defender, $armyStrategyDTO);

            return $this->render('Game/progress_battle.html.twig', [
                'player' => $player,
                'historicBattle' => $historicBattle,
                'defender' => $defender,
            ]);
        }

        return $this->render('Game/strategy.html.twig', [
            'kingdomArmys' => $kingdomArmys,
            'form' => $form->createView(),
        ]);
    }
}

<?php

namespace App\Controller\Game;

use App\Entity\Event;
use App\Entity\KingdomArmy;
use App\Entity\KingdomEvent;
use App\Entity\Player;
use App\Form\ArmyStrategyType;
use App\Model\ArmyStrategyDTO;
use App\Service\Event\ShadowDragonEvent;
use App\Service\Event\VerifyActionPointsManager;
use App\Service\Event\VerifyArmyManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;

class EventController extends Controller
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
     * @Route("/event", name="game_event")
     */
    public function eventAction(): Response
    {
        $events = $this->em->getRepository(Event::class)->findAll();

        return $this->render('Game/event.html.twig', ['events' => $events]);
    }

    /**
     * @Route("/participation-event/{id}", name="game_event_participate")
     */
    public function participateEventAction(Event $event, VerifyActionPointsManager $eventManager)
    {
        $player = $this->getUser();

        $verifyParticipation = $eventManager->isEventParticipate($event, $player);

        if (!$verifyParticipation) {
            $this->addFlash(
                'notice-danger',
                $this->translator->trans('messages.already-participate', [], 'game')
            );

            return $this->redirectToRoute('game_event');
        }
        // Verify action points and start event
        $isParticipate = $eventManager->verifyActionPoints($event, $player);

        if (!$isParticipate) {
            $this->addFlash(
                'notice-danger',
                $this->translator->trans('messages.unavailable-ap', [], 'game')
            );

            return $this->redirectToRoute('game_event');
        }

        $nameEvent = $event->getName();

        $this->addFlash(
            'notice',
            $this->translator->trans('messages.available-ap', ['%name%' => $nameEvent], 'game')
        );

        return $this->redirectToRoute('game_event');
    }

    /**
     * @Route("/strategie/event/{id}", name="game_event_strategy")
     */
    public function strategyAction(Event $event, Request $request, VerifyArmyManager $armyManager, ShadowDragonEvent $shadowDragonEvent)
    {
        /** @var Player $player */
        $player = $this->getUser();
        $kingdom = $player->getKingdom();

        $kingdomEvent = $this->em->getRepository(KingdomEvent::class)->getKingdomEvent($kingdom, $event);
        if ($kingdomEvent === null) {
            $this->addFlash(
                'notice-danger',
                $this->translator->trans('messages.unaivalable-participation', [], 'game')
            );
            return $this->redirectToRoute('game_event');
        }

        $armyStrategyDTO = new ArmyStrategyDTO();
        $kingdomArmys = $this->em->getRepository(KingdomArmy::class)->findBy(['kingdom' => $kingdom]);
        $form = $this->createForm(ArmyStrategyType::class, $armyStrategyDTO, ['kingdomArmys' => $kingdomArmys]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($player->getActionPoints() < Event::DRAGON_PRICE_BATTLE) {
                $this->addFlash(
                    'notice-danger',
                    $this->translator->trans('messages.unavailable-ap', [], 'game')
                );
                return $this->redirectToRoute('game_event_strategy', ['id' => $event->getId()]);
            }

            $isArmyValid = $armyManager->verifyArmy($armyStrategyDTO, $kingdomArmys);

            if (!$isArmyValid) {
                $this->addFlash(
                    'notice-danger',
                    $this->translator->trans('messages.unaivalable-army', [], 'game')
                );
                return $this->redirectToRoute('game_event_strategy', ['id' => $event->getId()]);
            }

            $remainingActionPoints = $player->getActionPoints() - Event::DRAGON_PRICE_BATTLE;
            $player->setActionPoints($remainingActionPoints);
            $this->em->flush();

            $historicBattle = $shadowDragonEvent->battle($kingdomArmys, $armyStrategyDTO, $event, $kingdom);

            return $this->render('Game/event_battle.html.twig', [
                'player' => $player,
                'historicBattle' => $historicBattle,
                'event' => $event
            ]);
        }

        return $this->render('Game/strategy.html.twig', [
            'kingdomArmys' => $kingdomArmys,
            'form' => $form->createView()
        ]);
    }

}

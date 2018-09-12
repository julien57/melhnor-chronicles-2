<?php

namespace App\Service\Event;

use App\Entity\Event;
use App\Entity\KingdomEvent;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;

class VerifyActionPointsManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function isEventParticipate(Event $event, Player $player) {

        $isParticipate = $this->em->getRepository(KingdomEvent::class)->getKingdomEvent($player->getKingdom(), $event);

        if ($isParticipate === null) {
            return true;
        }
        return false;
    }

    public function verifyActionPoints(Event $event, Player $player): bool
    {
        if ($player->getActionPoints() < $event->getAp()) {
            return false;
        }

        $playerActionPoints = $player->getActionPoints() - $event->getAp();
        $player->setActionPoints($playerActionPoints);

        $kingdomEvent = new KingdomEvent();
        $kingdomEvent->setKingdom($player->getKingdom());
        $kingdomEvent->setEvent($event);

        $this->em->persist($kingdomEvent);
        $this->em->flush();

        return true;
    }
}
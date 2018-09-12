<?php

namespace App\Service\Event;

use App\Entity\Army;
use App\Entity\KingdomArmy;
use App\Model\ArmyStrategyDTO;
use Doctrine\ORM\EntityManagerInterface;

class VerifyArmyManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function verifyArmy(ArmyStrategyDTO $armyStrategyDTO, array $kingdomArmys): bool
    {
        /** @var KingdomArmy $kingdomArmy */
        foreach ($kingdomArmys as $kingdomArmy) {
            if ($kingdomArmy->getArmy()->getId() === Army::SOLDIER_ID) {
                if ($kingdomArmy->getQuantity() < $armyStrategyDTO->getSoldier()) {
                    return false;
                }
                $remainingSoldiers = $kingdomArmy->getQuantity() - $armyStrategyDTO->getSoldier();
                $kingdomArmy->setQuantity($remainingSoldiers);
            }

            if ($kingdomArmy->getArmy()->getId() === Army::ARCHER_ID) {
                if ($kingdomArmy->getQuantity() < $armyStrategyDTO->getArcher()) {
                    return false;
                }
                $remainingArchers = $kingdomArmy->getQuantity() - $armyStrategyDTO->getArcher();
                $kingdomArmy->setQuantity($remainingArchers);
            }

            if ($kingdomArmy->getArmy()->getId() === Army::HORSEMAN_ID) {
                if ($kingdomArmy->getQuantity() < $armyStrategyDTO->getHorseman()) {
                    return false;
                }
                $remainingHorseMans = $kingdomArmy->getQuantity() - $armyStrategyDTO->getHorseman();
                $kingdomArmy->setQuantity($remainingHorseMans);
            }

            if ($kingdomArmy->getArmy()->getId() === Army::BOAT_ID) {
                if ($kingdomArmy->getQuantity() < $armyStrategyDTO->getBoat()) {
                    return false;
                }
                $remainingBoats = $kingdomArmy->getQuantity() - $armyStrategyDTO->getBoat();
                $kingdomArmy->setQuantity($remainingBoats);
            }
        }

        $this->em->flush();

        return true;
    }
}
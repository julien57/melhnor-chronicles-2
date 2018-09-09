<?php

namespace App\Service\Recruitment;

use App\Entity\Army;
use App\Entity\Kingdom;
use App\Entity\KingdomArmy;
use App\Entity\KingdomBuilding;
use App\Entity\KingdomResource;
use App\Entity\Resource;
use App\Model\ArmyRecruitmentDTO;
use Doctrine\ORM\EntityManagerInterface;

class ArmyRecruitment
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function recruitmentProcess(ArmyRecruitmentDTO $armyRecruitmentDTO, Kingdom $kingdom)
    {
        $kingdomArmys = $this->em->getRepository(KingdomArmy::class)->findByKingdom($kingdom);

        if ($armyRecruitmentDTO->getSoldier()) {
            $soldiers = $armyRecruitmentDTO->getSoldier();
            $isAvailableForSoldiers = $this->isPossibleForSoldier($soldiers, $kingdom);

            if (!$isAvailableForSoldiers) {
                return false;
            }

            /** @var KingdomArmy $kingdomArmy */
            foreach ($kingdomArmys as $kingdomArmy) {

                if ($kingdomArmy->getArmy()->getId() === Army::SOLDIER_ID) {
                    $totalSoldiers = $kingdomArmy->getQuantity() + $armyRecruitmentDTO->getSoldier();

                    $verifyMaxUnity = $this->verifyLimitUnity($kingdom, $totalSoldiers);

                    if (!$verifyMaxUnity) {
                        return false;
                    }

                    $kingdomArmy->setQuantity($totalSoldiers);
                }
            }
        }

        if ($armyRecruitmentDTO->getArcher()) {
            $archers = $armyRecruitmentDTO->getArcher();
            $isAvailableForArchers = $this->isPossibleForArcher($archers, $kingdom);

            if (!$isAvailableForArchers) {
                return false;
            }

            /** @var KingdomArmy $kingdomArmy */
            foreach ($kingdomArmys as $kingdomArmy) {

                if ($kingdomArmy->getArmy()->getId() === Army::ARCHER_ID) {
                    $totalArchers = $kingdomArmy->getQuantity() + $armyRecruitmentDTO->getArcher();

                    $verifyMaxUnity = $this->verifyLimitUnity($kingdom, $totalArchers);

                    if (!$verifyMaxUnity) {
                        return false;
                    }

                    $kingdomArmy->setQuantity($totalArchers);
                }
            }
        }

        if ($armyRecruitmentDTO->getHorseman()) {
            $horseman = $armyRecruitmentDTO->getHorseman();

            $isAvailableForHorseman = $this->isPossibleForHorseman($horseman, $kingdom);

            if (!$isAvailableForHorseman) {
                return false;
            }

            /** @var KingdomArmy $kingdomArmy */
            foreach ($kingdomArmys as $kingdomArmy) {

                if ($kingdomArmy->getArmy()->getId() === Army::HORSEMAN_ID) {
                    $totalHorsemans = $kingdomArmy->getQuantity() + $armyRecruitmentDTO->getHorseman();

                    $verifyMaxUnity = $this->verifyLimitUnity($kingdom, $totalHorsemans);

                    if (!$verifyMaxUnity) {
                        return false;
                    }

                    $kingdomArmy->setQuantity($totalHorsemans);
                }
            }
        }

        if ($armyRecruitmentDTO->getBoat()) {
            $boats = $armyRecruitmentDTO->getBoat();
            $isAvailableForBoats = $this->isPossibleForBoat($boats, $kingdom);

            if (!$isAvailableForBoats) {
                return false;
            }

            /** @var KingdomArmy $kingdomArmy */
            foreach ($kingdomArmys as $kingdomArmy) {

                if ($kingdomArmy->getArmy()->getId() === Army::BOAT_ID) {
                    $totalBoats = $kingdomArmy->getQuantity() + $armyRecruitmentDTO->getBoat();

                    $verifyMaxUnity = $this->verifyLimitUnity($kingdom, $totalBoats);

                    if (!$verifyMaxUnity) {
                        return false;
                    }

                    $kingdomArmy->setQuantity($totalBoats);
                }
            }
        }

        if (!$armyRecruitmentDTO->getSoldier() &&
            !$armyRecruitmentDTO->getArcher() &&
            !$armyRecruitmentDTO->getBoat() &&
            !$armyRecruitmentDTO->getHorseman()) {
            return false;
        }

        $this->em->flush();

        return true;
    }

    /**
     * @param $soldiers
     * @param Kingdom $kingdom
     *
     * @return bool
     */
    private function isPossibleForSoldier(int $soldiers, Kingdom $kingdom): bool
    {
        if ($kingdom->getPopulation() < $soldiers && $kingdom->getGold() < ($soldiers * Kingdom::SOLDIER_PRICE_UNITY)) {
            return false;
        }

        $resourcesId = [];
        /** @var KingdomResource $kingdomResource */
        foreach ($kingdom->getKingdomResources() as $kingdomResource) {
            if ($kingdomResource->getResource()->getId() === Resource::ARMOR_ID &&
                $kingdomResource->getQuantity() > $soldiers) {
                $armorQuantity = $kingdomResource->getQuantity() - $soldiers;
                $kingdomResource->setQuantity($armorQuantity);

                $resourcesId[] = Resource::ARMOR_ID;
            }
            if ($kingdomResource->getResource()->getId() === Resource::WEAPON_ID &&
                $kingdomResource->getQuantity() > $soldiers) {
                $weaponQuantity = $kingdomResource->getQuantity() - $soldiers;
                $kingdomResource->setQuantity($weaponQuantity);

                $resourcesId[] = Resource::WEAPON_ID;
            }
        }

        if (count($resourcesId) < 2) {
            $this->em->refresh($kingdomResource);

            return false;
        }

        $totalPopulation = $kingdom->getPopulation() - $soldiers;
        $kingdom->setPopulation($totalPopulation);

        $goldRequired = $soldiers * Kingdom::SOLDIER_PRICE_UNITY;
        $totalGold = $kingdom->getGold() - $goldRequired;

        if ($totalGold < 0) {
            return false;
        }
        $kingdom->setGold($totalGold);

        return true;
    }

    /**
     * @param int     $archers
     * @param Kingdom $kingdom
     *
     * @return bool
     */
    private function isPossibleForArcher(int $archers, Kingdom $kingdom): bool
    {
        if ($kingdom->getPopulation() < $archers && $kingdom->getGold() < ($archers * Kingdom::SOLDIER_PRICE_UNITY)) {
            return false;
        }

        $resourcesId = [];
        /** @var KingdomResource $kingdomResource */
        foreach ($kingdom->getKingdomResources() as $kingdomResource) {
            if ($kingdomResource->getResource()->getId() === Resource::ARMOR_ID &&
                $kingdomResource->getQuantity() > $archers) {
                $armorQuantity = $kingdomResource->getQuantity() - $archers;
                $kingdomResource->setQuantity($armorQuantity);

                $resourcesId[] = Resource::ARMOR_ID;
            }
            if ($kingdomResource->getResource()->getId() === Resource::BOW_ID &&
                $kingdomResource->getQuantity() > $archers) {
                $bowQuantity = $kingdomResource->getQuantity() - $archers;
                $kingdomResource->setQuantity($bowQuantity);

                $resourcesId[] = Resource::BOW_ID;
            }
        }

        if (count($resourcesId) < 2) {
            $this->em->refresh($kingdomResource);

            return false;
        }

        $totalPopulation = $kingdom->getPopulation() - $archers;
        $kingdom->setPopulation($totalPopulation);

        $goldRequired = $archers * Kingdom::SOLDIER_PRICE_UNITY;
        $totalGold = $kingdom->getGold() - $goldRequired;

        if ($totalGold < 0) {
            return false;
        }
        $kingdom->setGold($totalGold);

        return true;
    }

    private function isPossibleForHorseman(int $horseman, Kingdom $kingdom): bool
    {
        if ($kingdom->getPopulation() < $horseman || $kingdom->getGold() < ($horseman * Kingdom::HORSEMAN_PRICE_UNITY)) {
            return false;
        }

        $resourcesId = [];
        /** @var KingdomResource $kingdomResource */
        foreach ($kingdom->getKingdomResources() as $kingdomResource) {
            if ($kingdomResource->getResource()->getId() === Resource::ARMOR_ID &&
                $kingdomResource->getQuantity() > $horseman) {
                $armorQuantity = $kingdomResource->getQuantity() - $horseman;
                $kingdomResource->setQuantity($armorQuantity);

                $resourcesId[] = Resource::ARMOR_ID;
            }
            if ($kingdomResource->getResource()->getId() === Resource::WEAPON_ID &&
                $kingdomResource->getQuantity() > $horseman) {
                $weaponQuantity = $kingdomResource->getQuantity() - $horseman;
                $kingdomResource->setQuantity($weaponQuantity);

                $resourcesId[] = Resource::WEAPON_ID;
            }
            if ($kingdomResource->getResource()->getId() === Resource::HORSE_ID &&
                $kingdomResource->getQuantity() > $horseman) {
                $weaponQuantity = $kingdomResource->getQuantity() - $horseman;
                $kingdomResource->setQuantity($weaponQuantity);

                $resourcesId[] = Resource::HORSE_ID;
            }
        }

        if (count($resourcesId) < 3) {
            $this->em->refresh($kingdomResource);

            return false;
        }

        $totalPopulation = $kingdom->getPopulation() - $horseman;
        $kingdom->setPopulation($totalPopulation);

        $goldRequired = $horseman * Kingdom::HORSEMAN_PRICE_UNITY;
        $totalGold = $kingdom->getGold() - $goldRequired;
        $kingdom->setGold($totalGold);

        return true;
    }

    /**
     * @param int     $boats
     * @param Kingdom $kingdom
     *
     * @return bool
     */
    private function isPossibleForBoat(int $boats, Kingdom $kingdom): bool
    {
        if ($kingdom->getPopulation() < ($boats * 4) && $kingdom->getGold() < ($boats * Kingdom::BOAT_PRICE_UNITY)) {
            return false;
        }

        $resourcesId = [];
        /** @var KingdomResource $kingdomResource */
        foreach ($kingdom->getKingdomResources() as $kingdomResource) {
            if ($kingdomResource->getResource()->getId() === Resource::WOOD_ID &&
                $kingdomResource->getQuantity() > ($boats * 1000)) {
                $woodQuantity = $kingdomResource->getQuantity() - ($boats * 1000);
                $kingdomResource->setQuantity($woodQuantity);

                $resourcesId[] = Resource::WOOD_ID;
            }
            if ($kingdomResource->getResource()->getId() === Resource::IRON_ID &&
                $kingdomResource->getQuantity() > ($boats * 100)) {
                $ironQuantity = $kingdomResource->getQuantity() - ($boats * 100);
                $kingdomResource->setQuantity($ironQuantity);

                $resourcesId[] = Resource::IRON_ID;
            }
        }

        if (count($resourcesId) < 2) {
            $this->em->refresh($kingdomResource);

            return false;
        }

        $totalPopulation = $kingdom->getPopulation() - ($boats * 4);
        $kingdom->setPopulation($totalPopulation);

        $goldRequired = $boats * Kingdom::BOAT_PRICE_UNITY;
        $totalGold = $kingdom->getGold() - $goldRequired;

        if ($totalGold < 0) {
            return false;
        }
        $kingdom->setGold($totalGold);

        return true;
    }

    /**
     * @param Kingdom $kingdom
     * @param int     $unity
     *
     * @return bool
     */
    private function verifyLimitUnity(Kingdom $kingdom, int $unity): bool
    {
        /** @var KingdomBuilding $kingdomBuilding */
        foreach ($kingdom->getKingdomBuildings() as $kingdomBuilding) {
            if ($kingdomBuilding->getBuilding()->getId() === KingdomBuilding::BUILDING_RECRUITMENT_SOLDIER) {
                if ($kingdomBuilding->getMaxUnityArmy() < $unity) {
                    return false;
                }

                return true;
            }

            if ($kingdomBuilding->getBuilding()->getId() === KingdomBuilding::BUILDING_RECRUITMENT_ARCHERY) {
                if ($kingdomBuilding->getMaxUnityArmy() < $unity) {
                    return false;
                }

                return true;
            }

            if ($kingdomBuilding->getBuilding()->getId() === KingdomBuilding::BUILDING_RECRUITMENT_BOAT) {
                if ($kingdomBuilding->getMaxUnityArmy() < $unity) {
                    return false;
                }

                return true;
            }
        }
    }
}

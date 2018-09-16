<?php

namespace App\Service\Battle;

use App\Entity\Army;
use App\Entity\Kingdom;
use App\Entity\KingdomArmy;
use App\Entity\Message;
use App\Entity\Player;
use App\Model\ArmyStrategyDTO;
use Doctrine\ORM\EntityManagerInterface;

class PlayerVsPlayerManager
{
    /**
     * @var array
     */
    private $historic = [];

    /**
     * @var int
     */
    private $nbSoldiers;

    /**
     * @var int
     */
    private $nbArchers;

    /**
     * @var int
     */
    private $nbHorsemans;

    /**
     * @var int
     */
    private $nbBoats;

    /**
     * @var int
     */
    private $deadPopulationDefender = 0;

    /**
     * for look if army defender have army
     *
     * @var int
     */
    private $nbArmyDefender = 0;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function battle(Kingdom $kingdomAttacker, Player $defender, ArmyStrategyDTO $armyStrategyDTO)
    {
        $this->nbSoldiers = ($armyStrategyDTO->getSoldier()) ? $armyStrategyDTO->getSoldier() : 0;
        $this->nbArchers = ($armyStrategyDTO->getArcher()) ? $armyStrategyDTO->getArcher() : 0;
        $this->nbHorsemans = ($armyStrategyDTO->getHorseman()) ? $armyStrategyDTO->getHorseman() : 0;
        $this->nbBoats = ($armyStrategyDTO->getBoat()) ? $armyStrategyDTO->getBoat() : 0;

        $kingdomArmysDefender = $this->em->getRepository(KingdomArmy::class)->findBy(['kingdom' => $defender->getKingdom()]);

        $i = 0;
        /** @var KingdomArmy $kingdomArmyDefender */
        foreach ($kingdomArmysDefender as $kingdomArmyDefender) {
            if ($i === 0) {
                $this->historic[] = 'La région '.$kingdomArmyDefender->getKingdom()->getRegion()->getName()." semble plus vaste qu'il n'y parait...<br><br>";
                $this->historic[] = "L'armée pénètre dans le ".$defender->getKingdom()->getName().' et lance son attaque !<br><br>';
                $this->historic[] = "Vous débutez l'attaque avec ".$this->nbSoldiers.' soldats, '.$this->nbArchers.' archers, '.$this->nbHorsemans.' cavaliers et '.$this->nbBoats.' navires de guerre.';
            }

            if ($kingdomArmyDefender->getArmy()->getId() === Army::ARCHER_ID && $armyStrategyDTO->getArcher() > 0 && $kingdomArmyDefender->getQuantity() > 0) {
                $this->nbArmyDefender++;
                // ATTACK
                // Shot attacker
                $shotArchers = $this->nbArchers * $kingdomArmyDefender->getArmy()->getPower();
                $randomPrecision = $this->randomPrecisionShot(40, 65);
                $shotPrecision = ceil(($shotArchers * $randomPrecision) / 100);

                // Life defender
                $lifeDefender = $kingdomArmyDefender->getQuantity() * $kingdomArmyDefender->getArmy()->getLife();
                $remainingLifeDefender = $lifeDefender - $shotPrecision;
                $nbArchersDefender = ceil($remainingLifeDefender / $kingdomArmyDefender->getArmy()->getLife());
                $deadsArchers = $kingdomArmyDefender->getQuantity() - $nbArchersDefender;

                $this->historic[] = '<strong>Les archers</strong> tirent leurs flèches avec une précision de <strong>'.$randomPrecision."%</strong>. L'armée adverse vient de perdre ".$deadsArchers.' archers.';

                if ($nbArchersDefender < 0) {
                    $this->deadPopulation($kingdomArmyDefender, $armyStrategyDTO->getArcher());
                    $nbArchersDefender = 0;
                }

                $kingdomArmyDefender->setQuantity($nbArchersDefender);
                $this->em->flush();

                // DEFENCE
                $attackLess15 = ceil(($kingdomArmyDefender->getArmy()->getPower() * 15) / 100);
                $defenderAttack = ceil($this->randomPrecisionShot($kingdomArmyDefender->getArmy()->getPower(), $kingdomArmyDefender->getArmy()->getPower() + $attackLess15));
                $totalDefenderAttack = $defenderAttack * $kingdomArmyDefender->getQuantity();

                $this->historic[] = 'Les archers du <strong>'.$kingdomArmyDefender->getKingdom()->getName()."</strong> répliquent en lancant une attaque d'une puissance de ".$totalDefenderAttack.'.';

                $lifeArchers = $this->nbArchers * $kingdomArmyDefender->getArmy()->getLife();
                $lifeArchers = $lifeArchers - $totalDefenderAttack;
                $this->nbArchers = ceil($lifeArchers / $kingdomArmyDefender->getArmy()->getLife());

                if ($lifeArchers <= 0) {
                    $this->historic[] = 'Tous vos archers ont été tués...';
                    $this->nbArchers = 0;
                } else {
                    $this->historic[] = 'Malgré la riposte, il reste encore '.$this->nbArchers.' archers.';
                }

                $this->historic[] = 'Pour le moment, vous avez tué '.$deadsArchers.' archers.<br><br>';
            }

            if ($kingdomArmyDefender->getArmy()->getId() === Army::SOLDIER_ID && $armyStrategyDTO->getSoldier() > 0 && $kingdomArmyDefender->getQuantity() > 0) {
                $this->nbArmyDefender++;
                // ATTACK
                // Shot attacker
                $shotSoldiers = $this->nbSoldiers * $kingdomArmyDefender->getArmy()->getPower();
                $randomPrecision = $this->randomPrecisionShot(40, 65);
                $shotPrecision = ceil(($shotSoldiers * $randomPrecision) / 100);

                // Life defender
                $lifeDefender = $kingdomArmyDefender->getQuantity() * $kingdomArmyDefender->getArmy()->getLife();
                $remainingLifeDefender = $lifeDefender - $shotPrecision;
                $nbSoldiersDefender = ceil($remainingLifeDefender / $kingdomArmyDefender->getArmy()->getLife());
                $deadsSoldiers = $kingdomArmyDefender->getQuantity() - $nbSoldiersDefender;

                $this->historic[] = "<strong>Les soldats</strong> attaquent l'ennemi avec <strong>".$randomPrecision."%</strong> de précision. L'armée adverse vient de perdre ".$deadsSoldiers.' soldats.';

                if ($nbSoldiersDefender < 0) {
                    $this->deadPopulation($kingdomArmyDefender, $armyStrategyDTO->getSoldier());
                    $nbSoldiersDefender = 0;
                }

                $kingdomArmyDefender->setQuantity($nbSoldiersDefender);
                $this->em->flush();

                // DEFENCE
                $attackLess15 = ceil(($kingdomArmyDefender->getArmy()->getPower() * 25) / 100);
                $defenderAttack = ceil($this->randomPrecisionShot($kingdomArmyDefender->getArmy()->getPower(), $kingdomArmyDefender->getArmy()->getPower() + $attackLess15));
                $totalDefenderAttack = $defenderAttack * $kingdomArmyDefender->getQuantity();

                $this->historic[] = 'Les soldats du <strong>'.$kingdomArmyDefender->getKingdom()->getName().'</strong> ne se laissent pas abattre et répliquent avec une puissance de '.$totalDefenderAttack.'.';

                $lifeSoldats = $this->nbSoldiers * $kingdomArmyDefender->getArmy()->getLife();
                $lifeSoldats = $lifeSoldats - $totalDefenderAttack;
                $this->nbSoldiers = ceil($lifeSoldats / $kingdomArmyDefender->getArmy()->getLife());

                if ($lifeSoldats <= 0) {
                    $this->historic[] = 'Tous vos soldats ont été tués...';
                    $this->nbSoldiers = 0;
                } else {
                    $this->historic[] = 'Malgré la riposte ennemi, il reste encore '.$this->nbSoldiers.' soldats.';
                }

                $this->historic[] = 'Pour le moment, vous avez tué '.$deadsSoldiers.' soldats.<br><br>';
            }

            if ($kingdomArmyDefender->getArmy()->getId() === Army::HORSEMAN_ID && $armyStrategyDTO->getHorseman() > 0 && $kingdomArmyDefender->getQuantity() > 0) {
                $this->nbArmyDefender++;
                // ATTACK
                // Shot attacker
                $shotHorsemans = $this->nbHorsemans * $kingdomArmyDefender->getArmy()->getPower();
                $randomPrecision = $this->randomPrecisionShot(60, 80);
                $shotPrecision = ceil(($shotHorsemans * $randomPrecision) / 100);

                // Life defender
                $lifeDefender = $kingdomArmyDefender->getQuantity() * $kingdomArmyDefender->getArmy()->getLife();
                $remainingLifeDefender = $lifeDefender - $shotPrecision;
                $nbHorsemansDefender = ceil($remainingLifeDefender / $kingdomArmyDefender->getArmy()->getLife());
                $deadsHorsemans = $kingdomArmyDefender->getQuantity() - $nbHorsemansDefender;

                $this->historic[] = '<strong>Les cavaliers</strong> font des dégâts avec une précision de <strong>'.$randomPrecision."%</strong>. L'armée adverse vient de perdre ".$deadsHorsemans.' cavaliers.';

                if ($nbHorsemansDefender < 0) {
                    $this->deadPopulation($kingdomArmyDefender, $armyStrategyDTO->getHorseman());
                    $nbHorsemansDefender = 0;
                }

                $kingdomArmyDefender->setQuantity($nbHorsemansDefender);
                $this->em->flush();

                // DEFENCE
                $attackLess15 = ceil(($kingdomArmyDefender->getArmy()->getPower() * 25) / 100);
                $defenderAttack = ceil($this->randomPrecisionShot($kingdomArmyDefender->getArmy()->getPower(), $kingdomArmyDefender->getArmy()->getPower() + $attackLess15));
                $totalDefenderAttack = $defenderAttack * $kingdomArmyDefender->getQuantity();

                $this->historic[] = 'Les cavaliers du <strong>'.$kingdomArmyDefender->getKingdom()->getName()."</strong> répliquent en lancant une attaque d'une puissance de ".$totalDefenderAttack.'.';

                $lifeHorsemans = $this->nbHorsemans * $kingdomArmyDefender->getArmy()->getLife();
                $lifeHorsemans = $lifeHorsemans - $totalDefenderAttack;
                $this->nbHorsemans = ceil($lifeHorsemans / $kingdomArmyDefender->getArmy()->getLife());

                if ($lifeHorsemans <= 0) {
                    $this->historic[] = 'Tous vos cavaliers ont été tués...';
                    $this->nbHorsemans = 0;
                } else {
                    $this->historic[] = 'Malgré la riposte, il reste encore '.$this->nbHorsemans.' cavaliers.';
                }

                $this->historic[] = 'Pour le moment, vous avez tué '.$deadsHorsemans.' archers.<br><br>';
            }

            if ($kingdomArmyDefender->getArmy()->getId() === Army::BOAT_ID && $armyStrategyDTO->getBoat() > 0 && $kingdomArmyDefender->getQuantity() > 0) {
                $this->nbArmyDefender++;
                // ATTACK
                // Shot attacker
                $shotBoats = $this->nbBoats * $kingdomArmyDefender->getArmy()->getPower();
                $randomPrecision = $this->randomPrecisionShot(40, 65);
                $shotPrecision = ceil(($shotBoats * $randomPrecision) / 100);

                // Life defender
                $lifeDefender = $kingdomArmyDefender->getQuantity() * $kingdomArmyDefender->getArmy()->getLife();
                $remainingLifeDefender = $lifeDefender - $shotPrecision;
                $nbBoatsDefender = ceil($remainingLifeDefender / $kingdomArmyDefender->getArmy()->getLife());
                $deadsBoats = $kingdomArmyDefender->getQuantity() - $nbBoatsDefender;

                $this->historic[] = '<strong>Les navires de guerre</strong> touchent leur cible avec <strong>'.$randomPrecision."%</strong> de précision. L'armée adverse vient de perdre ".$deadsBoats.' navires.';

                if ($nbBoatsDefender < 0) {
                    $this->deadPopulation($kingdomArmyDefender, $armyStrategyDTO->getBoat());
                    $nbBoatsDefender = 0;
                }

                $kingdomArmyDefender->setQuantity($nbBoatsDefender);
                $this->em->flush();

                // DEFENCE
                $attackLess15 = ceil(($kingdomArmyDefender->getArmy()->getPower() * 15) / 100);
                $defenderAttack = ceil($this->randomPrecisionShot($kingdomArmyDefender->getArmy()->getPower(), $kingdomArmyDefender->getArmy()->getPower() + $attackLess15));
                $totalDefenderAttack = $defenderAttack * $kingdomArmyDefender->getQuantity();

                $this->historic[] = 'Les navires de guerre du <strong>'.$kingdomArmyDefender->getKingdom()->getName().'</strong> répliquent avec une puissance de feu de '.$totalDefenderAttack.'.';

                $lifeBoats = $this->nbBoats * $kingdomArmyDefender->getArmy()->getLife();
                $lifeBoats = $lifeBoats - $totalDefenderAttack;
                $this->nbBoats = ceil($lifeBoats / $kingdomArmyDefender->getArmy()->getLife());

                if ($lifeBoats <= 0) {
                    $this->historic[] = 'Tous vos navires de guerre ont coulé...';
                    $this->nbBoats = 0;
                } else {
                    $this->historic[] = 'Malgré la riposte, il vous reste encore '.$this->nbBoats.' navires.';
                }

                $this->historic[] = 'Pour le moment, vous avez détruit '.$deadsBoats.' navires de guerre.<br><br>';
            }

            if (($this->nbSoldiers <= 0 || $this->nbSoldiers === null) &&
                ($this->nbArchers <= 0 || $this->nbArchers === null) &&
                ($this->nbHorsemans <= 0 || $this->nbHorsemans === null) &&
                ($this->nbBoats <= 0 || $this->nbBoats === null)) {
                $this->historic[] = 'Toute votre armée a été anéantie !';
                $this->historic[] = "Après cette défaite, le chef des armées souhaite revenir plus tard pour terrasser l'ennemi, mais attention aux représailles...<br><br>";

                $deadPopulationDefender = ($this->deadPopulationDefender === 0) ? 'aucun habitant du '.$kingdomArmyDefender->getKingdom()->getName()." n'a péri." : $this->deadPopulationDefender.' habitants du '.$kingdomArmyDefender->getKingdom()->getName().' ont péri.';
                $this->historic[] = 'Au cours de cette bataille, '.$deadPopulationDefender;
                $this->finishBattle($kingdomAttacker);

                return $this->historic;
            }

            $i++;
        }

        // Send a message to defender
        $chiefArmy = $this->em->getRepository(Player::class)->getChiefArmy();
        $playerAttacker = $this->em->getRepository(Player::class)->findOneBy(['kingdom' => $kingdomAttacker]);
        $defender->setIsNotRead(true);

        $message = Message::messageBattleDefender(
            $chiefArmy,
            $playerAttacker,
            $defender,
            $this->deadPopulationDefender
        );
        $this->em->persist($message);
        $this->em->flush();

        // If defender not have army, attackant kill population
        if ($this->nbArmyDefender === 0) {
            $this->historic[] = "Cela ne sert à rien de lancer une bataille, il n'y a pas d'armée ennemie.";
            $populationDefender = $defender->getKingdom()->getPopulation();

            $randomDeadPopulation = $this->randomPrecisionShot(1, 4);
            $populationDead = ceil(($randomDeadPopulation * $populationDefender) / 150);

            $defender->getKingdom()->setPopulation($populationDefender - $populationDead);
            $this->finishBattle($kingdomAttacker);
            $this->em->flush();

            $this->historic[] = 'Le chef des armées a déclaré : <i>Malgré tout, nous avons quand même tué '.$populationDead.' habitants qui commençaient à se rebeller.</i>';

            return $this->historic;
        }

        $this->finishBattle($kingdomAttacker);
        $this->historic[] = 'L\'armée est à bout de force.<br> Sous les ordres de leur chef, l\'armée a préféré fuir. Le chef des armées a déclaré : "<i>C\'est une bataille de perdu, mais nous reviendrons lorsque nous serons plus fort !</i>".';
        $deadPopulationDefender = ($this->deadPopulationDefender === 0) ? "aucun habitant du royaume ennemi n'a péri." : $this->deadPopulationDefender.' habitants du royaume ennemi ont péri.';
        $this->historic[] = 'Au cours de cette bataille, '.$deadPopulationDefender;

        return $this->historic;
    }

    /**
     * If the battle is finish, kingdom retrieve the remaining army and save damage for each player
     *
     * @param $kingdomArmys
     */
    private function finishBattle(Kingdom $kingdomAttacker): void
    {
        $kingdomArmys = $this->em->getRepository(KingdomArmy::class)->findBy(['kingdom' => $kingdomAttacker]);

        /** @var KingdomArmy $kingdomArmy */
        foreach ($kingdomArmys as $kingdomArmy) {
            if ($kingdomArmy->getArmy()->getId() === Army::SOLDIER_ID) {
                $remainingSoldier = $kingdomArmy->getQuantity() + $this->nbSoldiers;
                $kingdomArmy->setQuantity($remainingSoldier);

            } elseif ($kingdomArmy->getArmy()->getId() === Army::ARCHER_ID) {
                $remainingArcher = $kingdomArmy->getQuantity() + $this->nbArchers;
                $kingdomArmy->setQuantity($remainingArcher);

            } elseif ($kingdomArmy->getArmy()->getId() === Army::HORSEMAN_ID) {
                $remainingHorseman = $kingdomArmy->getQuantity() + $this->nbHorsemans;
                $kingdomArmy->setQuantity($remainingHorseman);

            } elseif ($kingdomArmy->getArmy()->getId() === Army::BOAT_ID) {
                $remainingBoat = $kingdomArmy->getQuantity() + $this->nbBoats;
                $kingdomArmy->setQuantity($remainingBoat);
            }
        }

        $this->em->flush();
    }

    /**
     * If kingdom not have army, the attacker kill population
     *
     * @param KingdomArmy $kingdomArmy
     * @param int         $nbArmyAttacker
     */
    private function deadPopulation(KingdomArmy $kingdomArmy, int $nbArmyAttacker): void
    {
        $randomDeadPopulation = $this->randomPrecisionShot(5, 9);
        $populationDead = ceil(($randomDeadPopulation * $nbArmyAttacker) / 100);
        $nbPopulationDefender = $kingdomArmy->getKingdom()->getPopulation();
        $kingdomArmy->getKingdom()->setPopulation($nbPopulationDefender - $populationDead);

        $this->deadPopulationDefender = $this->deadPopulationDefender + $populationDead;

        $this->em->flush();
    }

    /**
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    private function randomPrecisionShot(int $min, int $max): int
    {
        return rand($min, $max);
    }
}

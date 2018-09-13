<?php

namespace App\Service\Event;

use App\Entity\Army;
use App\Entity\Event;
use App\Entity\Kingdom;
use App\Entity\KingdomArmy;
use App\Entity\KingdomEvent;
use App\Entity\Message;
use App\Entity\Player;
use App\Model\ArmyStrategyDTO;
use Doctrine\ORM\EntityManagerInterface;

class ShadowDragonEvent
{
    /**
     * @var array
     */
    private $historic = [];

    /**
     * @var int
     */
    private $nbArchers;

    /**
     * @var int
     */
    private $nbSoldiers;

    /**
     * @var int
     */
    private $nbHorseman;

    /**
     * @var int
     */
    private $nbBoat;

    /**
     * @var int
     */
    private $damageDragon = 0;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function battle(array $kingdomArmys, ArmyStrategyDTO $armyStrategyDTO, Event $event, Kingdom $kingdom): array
    {
        $this->historic[] = "L'armée s'approche doucement vers l'antre du dragon... Vous lancez l'attaque !<br><br>";

        $this->nbSoldiers = $armyStrategyDTO->getSoldier();
        $this->nbArchers = $armyStrategyDTO->getArcher();
        $this->nbHorseman = $armyStrategyDTO->getHorseman();
        $this->nbBoat = $armyStrategyDTO->getBoat();

        // KingdomEvent for use in battleFinish method in parameter
        $kingdomEvent = $this->em->getRepository(KingdomEvent::class)->getKingdomEvent($kingdom, $event);

        /** @var KingdomArmy $kingdomArmy */
        foreach ($kingdomArmys as $kingdomArmy) {
            if ($armyStrategyDTO->getArcher() !== null && $kingdomArmy->getArmy()->getId() === Army::ARCHER_ID && $this->nbArchers > 0) {
                $shotArchers = $this->nbArchers * $kingdomArmy->getArmy()->getPower();
                $randomPrecision = $this->randomPrecisionShot(65, 90);
                $shotPrecision = ceil(($shotArchers * $randomPrecision) / 100);

                $remainingDragon = $event->getLife() - $shotPrecision;
                $event->setLife($remainingDragon);
                $this->em->flush();

                $this->damageDragon = $this->damageDragon + $shotPrecision;
                $this->historic[] = '<strong>Les archers</strong> tirent leurs flèches avec une précision de <strong>'.$randomPrecision.'%</strong> et le dragon perd '.$shotPrecision.' de vie.';

                if ($remainingDragon <= 0) {
                    $this->historic[] = '<br><br><strong>Ca y est, le dragon a été vaincu grâce à la force de nombreux soldats !</strong>';
                    $this->finishBattle($kingdomArmys, $kingdomEvent);
                    $this->finishEvent($event, $kingdomEvent);
                    $this->em->remove($event);
                    $this->em->flush();

                    return $this->historic;
                }

                $attackLess15 = ceil(($event->getPower() * 25) / 100);
                $dragonAttack = ceil($this->randomPrecisionShot($event->getPower() - $attackLess15, $event->getPower() + $attackLess15));
                $this->historic[] = "<strong>Le dragon</strong> réplique en lancant une attaque d'une puissance de ".$dragonAttack.'.';

                $lifeArchers = $this->nbArchers * $kingdomArmy->getArmy()->getLife();
                $lifeArchers = $lifeArchers - $dragonAttack;

                $this->nbArchers = ceil($lifeArchers / $kingdomArmy->getArmy()->getLife());

                if ($lifeArchers <= 0) {
                    $this->historic[] = 'Tous les archers ont été tués...';
                    $this->nbArchers = 0;
                } else {
                    $this->historic[] = 'Il reste encore '.$this->nbArchers.' archers.';
                }
                $event->setLife($remainingDragon);
                $this->historic[] = 'Pour le moment, vous avez infligé '.$this->damageDragon." de dommage au <strong>dragon de l'ombre</strong>.<br><br>";
            }

            if ($armyStrategyDTO->getSoldier() !== null && $kingdomArmy->getArmy()->getId() === Army::SOLDIER_ID && $this->nbSoldiers > 0) {
                $shotSoldiers = $this->nbSoldiers * $kingdomArmy->getArmy()->getPower();
                $randomPrecision = $this->randomPrecisionShot(75, 90);
                $shotPrecision = ceil(($shotSoldiers * $randomPrecision) / 100);

                $remainingDragon = $event->getLife() - $shotPrecision;
                $event->setLife($remainingDragon);
                $this->em->flush();

                $this->damageDragon = $this->damageDragon + $shotPrecision;
                $this->historic[] = '<strong>Les soldats</strong> se lancent épée à la main avec <strong>'.$randomPrecision.'%</strong> de précision et le dragon perd '.$shotPrecision.' de vie.';

                if ($remainingDragon <= 0) {
                    $this->historic[] = '<br><br><strong>Ca y est, le dragon a été vaincu grâce à la force de nombreux soldats !</strong>';
                    $this->finishBattle($kingdomArmys, $kingdomEvent);
                    $this->finishEvent($event, $kingdomEvent);
                    $this->em->remove($event);
                    $this->em->flush();

                    return $this->historic;
                }

                $attackLess15 = ceil(($event->getPower() * 25) / 100);
                $dragonAttack = ceil($this->randomPrecisionShot($event->getPower() - $attackLess15, $event->getPower() + $attackLess15));
                $this->historic[] = "<strong>Le dragon</strong> réplique en lancant une attaque d'une puissance de ".$dragonAttack.'.';

                $lifeSoldiers = $this->nbSoldiers * $kingdomArmy->getArmy()->getLife();
                $lifeSoldiers = $lifeSoldiers - $dragonAttack;

                $this->nbSoldiers = ceil($lifeSoldiers / $kingdomArmy->getArmy()->getLife());

                if ($lifeSoldiers <= 0) {
                    $this->historic[] = 'Tous les soldats ont été tués...';
                    $this->nbSoldiers = 0;
                } else {
                    $this->historic[] = 'Il reste encore '.$this->nbSoldiers.' soldats.';
                }
                $event->setLife($remainingDragon);
                $this->historic[] = 'Pour le moment, vous avez infligé '.$this->damageDragon." de dommage au <strong>dragon de l'ombre</strong>.<br><br>";
            }

            if ($armyStrategyDTO->getHorseman() !== null && $kingdomArmy->getArmy()->getId() === Army::HORSEMAN_ID && $this->nbHorseman > 0) {
                $shotHorsemans = $this->nbHorseman * $kingdomArmy->getArmy()->getPower();
                $randomPrecision = $this->randomPrecisionShot(60, 75);
                $shotPrecision = ceil(($shotHorsemans * $randomPrecision) / 100);

                $remainingDragon = $event->getLife() - $shotPrecision;
                $event->setLife($remainingDragon);
                $this->em->flush();

                $this->damageDragon = $this->damageDragon + $shotPrecision;
                $this->historic[] = '<strong>Les cavaliers</strong> déboulent à toute vitesse arme à la main avec <strong>'.$randomPrecision.'%</strong> de précision et le dragon perd '.$shotPrecision.' de vie.';

                if ($remainingDragon <= 0) {
                    $this->historic[] = '<br><br><strong>Ca y est, le dragon a été vaincu grâce à la force de nombreux soldats !</strong>';
                    $this->finishBattle($kingdomArmys, $kingdomEvent);
                    $this->finishEvent($event, $kingdomEvent);
                    $this->em->remove($event);
                    $this->em->flush();

                    return $this->historic;
                }

                $attackLess15 = ceil(($event->getPower() * 25) / 100);
                $dragonAttack = ceil($this->randomPrecisionShot($event->getPower() - $attackLess15, $event->getPower() + $attackLess15));
                $this->historic[] = "<strong>Le dragon</strong> réplique en lancant une attaque d'une puissance de ".$dragonAttack.'.';

                $lifeHorsemans = $this->nbHorseman * $kingdomArmy->getArmy()->getLife();
                $lifeHorsemans = $lifeHorsemans - $dragonAttack;

                $this->nbHorseman = ceil($lifeHorsemans / $kingdomArmy->getArmy()->getLife());

                if ($lifeHorsemans <= 0) {
                    $this->historic[] = 'Tous les cavaliers ont été tués...';
                    $this->nbHorseman = 0;
                } else {
                    $this->historic[] = 'Il reste encore '.$this->nbHorseman.' cavaliers.';
                }
                $event->setLife($remainingDragon);
                $this->historic[] = 'Pour le moment, vous avez infligé '.$this->damageDragon." de dommage au <strong>dragon de l'ombre</strong>.<br><br>";
            }

            // Dragon Special attack FLAMETHROWER_ATTACK
            $dragonAttackType = rand(1, 10);
            if (($dragonAttackType === 5) && ($this->nbSoldiers > 0 || $this->nbArchers > 0 || $this->nbHorseman > 0 || $this->nbBoat > 0)) {
                $this->specialAttack($kingdomArmys, $event);
                if (($this->nbSoldiers <= 0 || $this->nbSoldiers === null) &&
                    ($this->nbArchers <= 0 || $this->nbArchers === null) &&
                    ($this->nbHorseman <= 0 || $this->nbHorseman === null) &&
                    ($this->nbBoat <= 0 || $this->nbBoat === null)) {
                    $this->historic[] = 'Toute votre armée a été décimée par la terrible attaque de la bête !';
                    $this->historic[] = 'Après cette terrible défaite, le chef des armées souhaite revenir plus tard pour terrasser ce maudit dragon, lorsque nous serons beaucoup plus fort...';
                    $this->finishBattle($kingdomArmys, $kingdomEvent);

                    return $this->historic;
                } else {
                    $this->historic[] = 'Après cette attaque dévastatrice, il reste '.$this->nbSoldiers.' soldats, '.$this->nbArchers.' archers, '.$this->nbHorseman.' cavaliers et '.$this->nbBoat.' navires.<br><br>';
                }
            }

            if ($armyStrategyDTO->getBoat() !== null && $kingdomArmy->getArmy()->getId() === Army::BOAT_ID && $this->nbBoat > 0) {
                $shotBoats = $this->nbBoat * $kingdomArmy->getArmy()->getPower();
                $randomPrecision = $this->randomPrecisionShot(80, 90);
                $shotPrecision = ceil(($shotBoats * $randomPrecision) / 100);

                $remainingDragon = $event->getLife() - $shotPrecision;
                $event->setLife($remainingDragon);
                $this->em->flush();

                $this->damageDragon = $this->damageDragon + $shotPrecision;
                $this->historic[] = '<strong>Les navires de guerre</strong> mettent du temps à se placer mais tirent enfin avec une précision de <strong>'.$randomPrecision.'%</strong> et le dragon perd '.$shotPrecision.' de vie.';

                if ($remainingDragon <= 0) {
                    $this->historic[] = '<br><br><strong>Ca y est, le dragon a été vaincu grâce à la force de nombreux soldats !</strong>';
                    $this->finishBattle($kingdomArmys, $kingdomEvent);
                    $this->finishEvent($event, $kingdomEvent);
                    $this->em->remove($event);
                    $this->em->flush();

                    return $this->historic;
                }

                // Boats are vulnerable with the dragon
                $attackMin = $event->getPower() + 30;
                $attackMax100 = $event->getPower() + 100;
                $dragonAttack = ceil($this->randomPrecisionShot($attackMin, $attackMax100));
                $this->historic[] = "<strong>Le dragon</strong> réplique en lancant une attaque de boules de feu d'une puissance de ".$dragonAttack.".<br> Les navires ont l'air vulnérable face à ce monstre.";

                $lifeBoats = $this->nbBoat * $kingdomArmy->getArmy()->getLife();
                $lifeBoats = $lifeBoats - $dragonAttack;

                $this->nbBoat = ceil($lifeBoats / $kingdomArmy->getArmy()->getLife());

                if ($lifeBoats <= 0) {
                    $this->historic[] = 'Tous les navires ont été détruits...';
                    $this->nbBoat = 0;
                } else {
                    $this->historic[] = 'Il reste encore '.$this->nbBoat.' navires de guerre.';
                }
                $event->setLife($remainingDragon);
                $this->historic[] = 'Pour le moment, vous avez infligé '.$this->damageDragon." de dommage au <strong>dragon de l'ombre</strong>.<br><br>";
            }

            if ($armyStrategyDTO->getArcher() !== null && $kingdomArmy->getArmy()->getId() === Army::ARCHER_ID && $this->nbArchers > 0) {
                $shotArchers = $this->nbArchers * $kingdomArmy->getArmy()->getPower();
                $randomPrecision = $this->randomPrecisionShot(65, 90);
                $shotPrecision = ceil(($shotArchers * $randomPrecision) / 100);

                $remainingDragon = $event->getLife() - $shotPrecision;
                $event->setLife($remainingDragon);
                $this->em->flush();

                $this->damageDragon = $this->damageDragon + $shotPrecision;
                $this->historic[] = '<strong>Les archers</strong> tirent une ultime salve de flèches avec une précision de <strong>'.$randomPrecision.'%</strong>, le dragon perd '.$shotPrecision.' de vie.';

                if ($remainingDragon <= 0) {
                    $this->historic[] = '<strong>Ca y est, le dragon a été vaincu grâce à la force de nombreux soldats !</strong>';
                    $this->finishBattle($kingdomArmys, $kingdomEvent);
                    $this->finishEvent($event, $kingdomEvent);
                    $this->em->remove($event);
                    $this->em->flush();

                    return $this->historic;
                }

                $attackLess15 = ceil(($event->getPower() * 25) / 100);
                $dragonAttack = ceil($this->randomPrecisionShot($event->getPower() - $attackLess15, $event->getPower() + $attackLess15));
                $this->historic[] = "<strong>Le dragon</strong> réplique encore en lancant une attaque d'une puissance de ".$dragonAttack.'.';

                $lifeArchers = $this->nbArchers * $kingdomArmy->getArmy()->getLife();
                $lifeArchers = $lifeArchers - $dragonAttack;

                $this->nbArchers = ceil($lifeArchers / $kingdomArmy->getArmy()->getLife());

                if ($lifeArchers <= 0) {
                    $this->historic[] = 'Tous les archers ont été tués...';
                    $this->nbArchers = 0;
                } else {
                    $this->historic[] = 'Il reste encore '.$this->nbArchers.' archers.';
                }
                $event->setLife($remainingDragon);
                $this->historic[] = 'Pour le moment, vous avez infligé '.$this->damageDragon." de dommage au <strong>dragon de l'ombre</strong>.<br><br>";
            }

            if ($armyStrategyDTO->getSoldier() !== null && $kingdomArmy->getArmy()->getId() === Army::SOLDIER_ID && $this->nbSoldiers > 0) {
                $shotSoldiers = $this->nbSoldiers * $kingdomArmy->getArmy()->getPower();
                $randomPrecision = $this->randomPrecisionShot(65, 90);
                $shotPrecision = ceil(($shotSoldiers * $randomPrecision) / 100);

                $remainingDragon = $event->getLife() - $shotPrecision;
                $event->setLife($remainingDragon);
                $this->em->flush();

                $this->damageDragon = $this->damageDragon + $shotPrecision;
                $this->historic[] = "<strong>Les soldats</strong> continuent à blesser le dragon à coup d'épées avec <strong>".$randomPrecision.'%</strong> de précision. Le dragon vient de perdre '.$shotPrecision.' de vie.';

                if ($remainingDragon <= 0) {
                    $this->historic[] = "<strong>Ca y est, le dragon a été vaincu grâce de toute l'armée !</strong>";
                    $this->finishBattle($kingdomArmys, $kingdomEvent);
                    $this->finishEvent($event, $kingdomEvent);
                    $this->em->remove($event);
                    $this->em->flush();

                    return $this->historic;
                }

                $attackLess15 = ceil(($event->getPower() * 25) / 100);
                $dragonAttack = ceil($this->randomPrecisionShot($event->getPower() - $attackLess15, $event->getPower() + $attackLess15));
                $this->historic[] = "<strong>Le dragon</strong> réplique en lancant une attaque d'une puissance de ".$dragonAttack.'.';

                $lifeSoldiers = $this->nbSoldiers * $kingdomArmy->getArmy()->getLife();
                $lifeSoldiers = $lifeSoldiers - $dragonAttack;

                $this->nbSoldiers = ceil($lifeSoldiers / $kingdomArmy->getArmy()->getLife());

                if ($lifeSoldiers <= 0) {
                    $this->historic[] = 'Tous les soldats ont été tués...';
                    $this->nbSoldiers = 0;
                } else {
                    $this->historic[] = 'Il reste encore '.$this->nbSoldiers.' soldats.';
                }
                $event->setLife($remainingDragon);
                $this->historic[] = 'Pour le moment, vous avez infligé '.$this->damageDragon." de dommage au <strong>dragon de l'ombre</strong>.<br><br>";
            }

            if ($armyStrategyDTO->getHorseman() !== null && $kingdomArmy->getArmy()->getId() === Army::HORSEMAN_ID && $this->nbHorseman > 0) {
                $shotHorsemans = $this->nbHorseman * $kingdomArmy->getArmy()->getPower();
                $randomPrecision = $this->randomPrecisionShot(65, 90);
                $shotPrecision = ceil(($shotHorsemans * $randomPrecision) / 100);

                $remainingDragon = $event->getLife() - $shotPrecision;
                $event->setLife($remainingDragon);
                $this->em->flush();

                $this->damageDragon = $this->damageDragon + $shotPrecision;
                $this->historic[] = '<strong>Les cavaliers</strong> sur place ont des difficultés à se déplacer sur ce sol rocheux. Avec <strong>'.$randomPrecision.'%</strong> de précision, le dragon perd '.$shotPrecision.' de vie.';

                if ($remainingDragon <= 0) {
                    $this->historic[] = '<strong>Ca y est, le dragon a été vaincu grâce à la force de nombreux soldats !</strong>';
                    $this->finishBattle($kingdomArmys, $kingdomEvent);
                    $this->finishEvent($event, $kingdomEvent);
                    $this->em->remove($event);
                    $this->em->flush();

                    return $this->historic;
                }

                $attackLess15 = ceil(($event->getPower() * 25) / 100);
                $dragonAttack = ceil($this->randomPrecisionShot($event->getPower() - $attackLess15, $event->getPower() + $attackLess15));
                $this->historic[] = "<strong>Le dragon</strong> réplique en lancant une attaque d'une puissance de ".$dragonAttack.'.';

                $lifeHorsemans = $this->nbHorseman * $kingdomArmy->getArmy()->getLife();
                $lifeHorsemans = $lifeHorsemans - $dragonAttack;

                $this->nbHorseman = ceil($lifeHorsemans / $kingdomArmy->getArmy()->getLife());

                if ($lifeHorsemans <= 0) {
                    $this->historic[] = 'Tous les cavaliers ont été tués...';
                    $this->nbHorseman = 0;
                } else {
                    $this->historic[] = 'Il reste encore '.$this->nbHorseman.' cavaliers.';
                }
                $event->setLife($remainingDragon);
                $this->historic[] = 'Pour le moment, vous avez infligé '.$this->damageDragon." de dommage au <strong>dragon de l'ombre</strong>.<br><br>";
            }

            if (($this->nbSoldiers <= 0 || $this->nbSoldiers === null) &&
                ($this->nbArchers <= 0 || $this->nbArchers === null) &&
                ($this->nbHorseman <= 0 || $this->nbHorseman === null) &&
                ($this->nbBoat <= 0 || $this->nbBoat === null)) {
                $this->historic[] = 'Toute votre armée a été décimée par le dragon !';
                $this->historic[] = 'Après cette terrible défaite, le chef des armées souhaite revenir plus tard pour terrasser ce maudit dragon, lorsque nous serons beaucoup plus fort...';
                $this->finishBattle($kingdomArmys, $kingdomEvent);

                return $this->historic;
            }
        }

        $this->finishBattle($kingdomArmys, $kingdomEvent);
        $this->historic[] = 'L\'armée est à bout de force.<br> Sous les ordres de leur chef, l\'armée a préféré fuir. Le chef des armées a déclaré : "<i>C\'est une bataille de perdu, mais nous reviendrons lorsque nous serons plus fort !</i>".';

        return $this->historic;
    }

    private function specialAttack(array $kingdomArmys, Event $event)
    {
        $this->historic[] = 'Dans une rage folle, le dragon lance une <strong>'.Event::FLAMETHROWER_ATTACK_NAME.'</strong>, trois attaques en même temps !';

        foreach ($kingdomArmys as $kingdomArmy) {
            if ($this->nbArchers > 0 && $kingdomArmy->getArmy()->getId() === Army::ARCHER_ID) {
                $lifeArchers = $this->nbArchers * $kingdomArmy->getArmy()->getLife();

                $dragonRandomSpecial = $this->randomPrecisionShot($event->getSpecialAttack() - 20, $event->getSpecialAttack() + 20);

                $lifeArchers = $lifeArchers - $dragonRandomSpecial;

                $this->historic[] = "L'attaque spéciale du <strong>dragon de l'ombre</strong> a infligé ".$dragonRandomSpecial.' de dégâts aux archers !';

                if ($lifeArchers <= 0) {
                    $this->historic[] = "Tous les archers ont été tués à cause de l'attaque surpuissante du dragon...<br><br>";
                    $this->nbArchers = 0;
                } else {
                    $this->nbArchers = ceil($lifeArchers / $kingdomArmy->getArmy()->getLife());
                    $this->historic[] = "Malgré l'attaque spéciale du dragon, il reste ".$this->nbArchers.' archers.<br><br>';
                }
            }

            if ($this->nbSoldiers > 0 && $kingdomArmy->getArmy()->getId() === Army::SOLDIER_ID) {
                $lifeSoldiers = $this->nbSoldiers * $kingdomArmy->getArmy()->getLife();

                $dragonRandomSpecial = $this->randomPrecisionShot($event->getSpecialAttack() - 20, $event->getSpecialAttack() + 20);

                $lifeSoldiers = $lifeSoldiers - $dragonRandomSpecial;

                $this->historic[] = "L'attaque spéciale du <strong>dragon de l'ombre</strong> a infligé ".$dragonRandomSpecial.' de dégâts aux soldats !';

                if ($lifeSoldiers <= 0) {
                    $this->historic[] = "Tous les soldats ont été tués à cause de l'attaque surpuissante du dragon...<br><br>";
                    $this->nbSoldiers = 0;
                } else {
                    $this->nbSoldiers = ceil($lifeSoldiers / $kingdomArmy->getArmy()->getLife());
                    $this->historic[] = "Malgré l'attaque spéciale du dragon, il reste ".$this->nbSoldiers.' soldats.<br><br>';
                }
            }

            if ($this->nbHorseman > 0 && $kingdomArmy->getArmy()->getId() === Army::HORSEMAN_ID) {
                $lifeHorseman = $this->nbHorseman * $kingdomArmy->getArmy()->getLife();

                $dragonRandomSpecial = $this->randomPrecisionShot($event->getSpecialAttack() - 20, $event->getSpecialAttack() + 20);

                $lifeHorseman = $lifeHorseman - $dragonRandomSpecial;

                $this->historic[] = "L'attaque spéciale du <strong>dragon de l'ombre</strong> a infligé ".$dragonRandomSpecial.' de dégâts aux cavaliers !';

                if ($lifeHorseman <= 0) {
                    $this->historic[] = "Tous les cavaliers ont été tués à cause de l'attaque surpuissante du dragon...<br><br>";
                    $this->nbHorseman = 0;
                } else {
                    $this->nbHorseman = ceil($lifeHorseman / $kingdomArmy->getArmy()->getLife());
                    $this->historic[] = "Malgré l'attaque spéciale du dragon, il reste ".$this->nbHorseman.' cavaliers.<br><br>';
                }
            }

            if ($this->nbBoat > 0 && $kingdomArmy->getArmy()->getId() === Army::BOAT_ID) {
                $lifeBoat = $this->nbBoat * $kingdomArmy->getArmy()->getLife();

                $dragonRandomSpecial = $this->randomPrecisionShot($event->getSpecialAttack() - 20, $event->getSpecialAttack() + 20);

                $lifeBoat = $lifeBoat - $dragonRandomSpecial;

                $this->historic[] = "L'attaque spéciale du <strong>dragon de l'ombre</strong> a infligé ".$dragonRandomSpecial.' de dégâts aux navires avec une boule de feu géante !';

                if ($lifeBoat <= 0) {
                    $this->historic[] = "Aucun navire de guerre n'a survécu à cette attaque de feu...<br><br>";
                    $this->nbBoat = 0;
                } else {
                    $this->nbBoat = ceil($lifeBoat / $kingdomArmy->getArmy()->getLife());
                    $this->historic[] = "Malgré l'attaque spéciale du dragon, il reste ".$this->nbBoat.' navires.<br><br>';
                }
            }
        }
    }

    /**
     * If the battle is finish, kingdom retrieve the remaining army and save damage for each player
     *
     * @param $kingdomArmys
     */
    private function finishBattle(array $kingdomArmys, KingdomEvent $kingdomEvent): void
    {
        /** @var KingdomArmy $kingdomArmy */
        foreach ($kingdomArmys as $kingdomArmy) {
            if ($kingdomArmy->getId() === Army::SOLDIER_ID) {
                $remainingSoldier = $kingdomArmy->getQuantity() + $this->nbSoldiers;
                $kingdomArmy->setQuantity($remainingSoldier);
            } elseif ($kingdomArmy->getId() === Army::ARCHER_ID) {
                $remainingArcher = $kingdomArmy->getQuantity() + $this->nbArchers;
                $kingdomArmy->setQuantity($remainingArcher);
            } elseif ($kingdomArmy->getId() === Army::HORSEMAN_ID) {
                $remainingHorseman = $kingdomArmy->getQuantity() + $this->nbHorseman;
                $kingdomArmy->setQuantity($remainingHorseman);
            } elseif ($kingdomArmy->getId() === Army::BOAT_ID) {
                $remainingBoat = $kingdomArmy->getQuantity() + $this->nbBoat;
                $kingdomArmy->setQuantity($remainingBoat);
            }
        }

        $kingdomEvent->setDamage($kingdomEvent->getDamage() + $this->damageDragon);
        $this->em->flush();
    }

    /**
     * If the dragon is dead, remove the event
     *
     * @param Event $event
     */
    private function finishEvent(Event $event, KingdomEvent $kingdomEvent)
    {
        $kingdomsEvent = $this->em->getRepository(KingdomEvent::class)->findBy(['event' => $event]);

        $dammages = [];
        foreach ($kingdomsEvent as $kingdomEvent) {
            $dammages[] = $kingdomEvent->getDamage();
        }

        $winner = $this->em->getRepository(KingdomEvent::class)->findOneBy(['damage' => max($dammages)]);

        if ($winner->getId() === $kingdomEvent->getId()) {
            $this->historic[] = "Bravo ! Vous avez gagné cet event et vous avez assené le coup de grâce à la créature, vous remportez donc 15 000 pièces d'or !";
            $kingdomPlayer = $kingdomEvent->getKingdom();
            $kingdomPlayer->setGold($kingdomPlayer->getGold() + 15000);
        } else {
            /** @var Kingdom $kingdomWinner */
            $kingdomWinner = $winner->getKingdom();
            $kingdomWinner->setGold($kingdomWinner->getGold() + 10000);
            $this->historic[] = "Le gagnant de l'évènement est : ".$kingdomWinner->getName().'.';

            $kingdomPlayer = $kingdomEvent->getKingdom();
            $kingdomPlayer->setGold($kingdomPlayer->getGold() + 5000);
            $this->historic[] = "Comme vous avez assené le coup de grâce, vous remportez 5000 pièces d'or !";

            /** @var Player $playerWin */
            $playerWin = $this->em->getRepository(Player::class)->findByKingdom($kingdomWinner);
            $playerWin->setIsNotRead(true);

            $chiefArmy = $this->em->getRepository(Player::class)->getChiefArmy();

            $message = Message::messageForWinner(
                $chiefArmy,
                $playerWin,
                $event
            );
            $this->em->persist($message);
        }

        foreach ($kingdomsEvent as $kingdomEvent) {
            $this->em->remove($kingdomEvent);
        }
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

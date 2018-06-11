<?php

namespace App\Service\Player;

use App\Entity\KingdomResource;
use App\Entity\Player;
use App\Entity\Resource;
use Doctrine\ORM\EntityManagerInterface;

class InitGamePlayerManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createKingdom($registrationDTO)
    {
        dump($registrationDTO); die();
        $player = Player::initPlayer($registrationDTO, $kingdom);

        // Init Meat
        /** @var Resource $meat */
        $meat = $this->em->getRepository(Resource::class)->find(Resource::MEAT_ID);

        $initKingdomMeat = new KingdomResource(
            $kingdom,
            $meat,
            KingdomResource::MEAT_STARTER_QUANTITY
        );

        // Init Wood
        /** @var Resource $wood */
        $wood = $this->em->getRepository(Resource::class)->find(Resource::WOOD_ID);

        $initKingdomWood = new KingdomResource(
            $kingdom,
            $wood,
            KingdomResource::WOOD_STARTER_QUANTITY
        );

        // Init Stone
        /** @var Resource $stone */
        $stone = $this->em->getRepository(Resource::class)->find(Resource::STONE_ID);

        $initKingdomStone = new KingdomResource(
            $kingdom,
            $stone,
            KingdomResource::STONE_STARTER_QUANTITY
        );

        $initKingdomMeat->initKingdomResource();
        $initKingdomWood->initKingdomResource();
        $initKingdomStone->initKingdomResource();

        $this->em->persist($initKingdomMeat);
        $this->em->persist($initKingdomWood);
        $this->em->persist($initKingdomStone);
        $this->em->persist($player);

        $this->em->flush();

    }
}
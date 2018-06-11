<?php

namespace App\Service\Player;

use App\Entity\Kingdom;
use App\Entity\KingdomResource;
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

    /**
     * @param Kingdom $kingdom
     */
    public function initKingdomResources(Kingdom $kingdom): void
    {
        $initKingdomMeat = $this->initResource(
            Resource::MEAT_ID,
            $kingdom,
            KingdomResource::MEAT_STARTER_QUANTITY
        );

        $initKingdomWood = $this->initResource(
            Resource::WOOD_ID,
            $kingdom,
            KingdomResource::WOOD_STARTER_QUANTITY
        );

        $initKingdomStone = $this->initResource(
            Resource::STONE_ID,
            $kingdom,
            KingdomResource::STONE_STARTER_QUANTITY
        );

        $this->em->persist($initKingdomMeat);
        $this->em->persist($initKingdomWood);
        $this->em->persist($initKingdomStone);

        $this->em->flush();
    }

    /**
     * @param int $resourceId
     * @param Kingdom $kingdom
     * @param int $quantity
     * @return KingdomResource
     */
    private function initResource(int $resourceId, Kingdom $kingdom, int $quantity): KingdomResource
    {
        /** @var Resource $resource */
        $resource = $this->em->getRepository(Resource::class)->find($resourceId);

        $kingdomResource = new KingdomResource(
            $kingdom,
            $resource,
            $quantity
        );

        return $kingdomResource;
    }
}
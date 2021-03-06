<?php

namespace App\Service\Player;

use App\Entity\Army;
use App\Entity\Kingdom;
use App\Entity\KingdomArmy;
use App\Entity\KingdomResource;
use App\Entity\Player;
use App\Entity\Resource;
use App\Model\CreatePlayerDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class InitGamePlayerManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, SessionInterface $session, RouterInterface $router, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->session = $session;
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * @param CreatePlayerDTO $createPlayerDTO
     *
     * @return RedirectResponse
     */
    public function initPlayerWithKingdom(CreatePlayerDTO $createPlayerDTO): RedirectResponse
    {
        $kingdom = Kingdom::initKingdom($createPlayerDTO);
        $player = Player::initPlayer($createPlayerDTO, $kingdom);

        $this->initArmy($kingdom, Army::SOLDIER_ID);
        $this->initArmy($kingdom, Army::ARCHER_ID);
        $this->initArmy($kingdom, Army::HORSEMAN_ID);
        $this->initArmy($kingdom, Army::BOAT_ID);

        $this->em->persist($kingdom);
        $this->em->persist($player);
        $this->em->flush();

        $this->session->getFlashBag()->add('notice', $this->translator->trans('messages.service-init-player-welcome', [], 'game'));

        return new RedirectResponse($this->router->generate('game_security_login'));
    }

    public function initArmy(Kingdom $kingdom, $armyId)
    {
        $army = $this->em->getRepository(Army::class)->find($armyId);
        $initKingdomArmy = KingdomArmy::initKingdomArmy($kingdom, $army);

        $this->em->persist($initKingdomArmy);
        $this->em->flush();
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
     * @param int     $resourceId
     * @param Kingdom $kingdom
     * @param int     $quantity
     *
     * @return KingdomResource
     */
    private function initResource(int $resourceId, Kingdom $kingdom, int $quantity): KingdomResource
    {
        /** @var resource $resource */
        $resource = $this->em->getRepository(Resource::class)->find($resourceId);

        $kingdomResource = new KingdomResource(
            $kingdom,
            $resource,
            $quantity
        );

        return $kingdomResource;
    }
}

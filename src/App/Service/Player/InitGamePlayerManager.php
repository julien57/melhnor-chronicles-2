<?php

namespace App\Service\Player;

use App\Entity\Kingdom;
use App\Entity\KingdomResource;
use App\Entity\Player;
use App\Entity\Resource;
use App\Model\CreatePlayerDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

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

    public function __construct(EntityManagerInterface $em, SessionInterface $session, RouterInterface $router)
    {
        $this->em = $em;
        $this->session = $session;
        $this->router = $router;
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

        $this->em->persist($kingdom);
        $this->em->persist($player);
        $this->em->flush();

        $this->session->getFlashBag()->add('notice', 'Bienvenue sur Melhnor, vous pouvez maintenant vous connecter !');

        return new RedirectResponse($this->router->generate('security_login'));
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

<?php

namespace App\Controller\Donjon;

use App\Entity\KingdomResource;
use App\Entity\Message;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayerCardController extends Controller
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
     * @param int $id
     *
     * @return Response
     *
     * @Route("fiche-joueur/{id}", requirements={"\d+"}, name="playerCard")
     */
    public function playerCardAction(int $id): Response
    {
        $player = $this->getDoctrine()->getRepository(Player::class)->find($id);

        $messages = $this->em->getRepository(Message::class)->findBySender($player);

        return $this->render('Donjon/player_card.html.twig', [
            'player' => $player,
            'messages' => $messages
        ]);
    }

    /**
     * @return RedirectResponse
     *
     * @Route("suppression-joueur/{id}", requirements={"\d+"}, name="remove-player")
     */
    public function removePlayer(int $id): RedirectResponse
    {
        $player = $this->getDoctrine()->getRepository(Player::class)->find($id);
        $kingdomResources = $this->em->getRepository(KingdomResource::class)->findByKingdom($player->getKingdom());

        foreach ($kingdomResources as $kingdomResource) {
            $this->em->remove($kingdomResource);
        }
        $this->em->remove($player);
        $this->em->flush();

        $this->addflash('notice', 'Le joueur a bien été supprimé !');
        return $this->redirectToRoute('donjon');
    }
}

<?php

namespace App\Controller\Donjon;

use App\Entity\KingdomResource;
use App\Entity\Message;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class PlayerCardController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * @param int $id
     *
     * @return Response
     *
     * @Route("fiche-joueur/{id}", requirements={"\d+"}, name="donjon_player_card")
     */
    public function playerCardAction(Player $player): Response
    {
        $messages = $this->em->getRepository(Message::class)->findBySender($player);

        return $this->render('Donjon/player_card.html.twig', [
            'player' => $player,
            'messages' => $messages
        ]);
    }

    /**
     * @return RedirectResponse
     *
     * @Route("fiche-joueur/{id}/suppression", requirements={"\d+"}, name="donjon_player_card_remove")
     */
    public function removeAction(Player $player): RedirectResponse
    {
        $kingdomResources = $this->em->getRepository(KingdomResource::class)->findByKingdom($player->getKingdom());

        foreach ($kingdomResources as $kingdomResource) {
            $this->em->remove($kingdomResource);
        }
        $this->em->remove($player);
        $this->em->flush();

        $this->addflash('notice', $this->translator->trans('messages.deleted-player', [], 'donjon'));
        return $this->redirectToRoute('donjon_index');
    }
}

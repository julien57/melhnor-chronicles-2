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
     * @param Request $request
     * @param int     $id
     *
     * @return RedirectResponse|Response
     *
     * @Route("fiche-joueur/{id}", requirements={"\d+"}, name="playerCard")
     */
    public function playerCardAction(Request $request, EntityManagerInterface $em, int $id)
    {
        $player = $this->getDoctrine()->getRepository(Player::class)->find($id);

        $form = $this->get('form.factory')->create();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $kingdomResources = $em->getRepository(KingdomResource::class)->findByKingdom($player->getKingdom());

            foreach ($kingdomResources as $kingdomResource) {
                $em->remove($kingdomResource);
            }
            $em->remove($player);
            $em->flush();

            $this->addflash('notice', 'Le joueur a bien été supprimé !');

            return $this->redirectToRoute('donjon');
        }

        $messages = $em->getRepository(Message::class)->findBySender($player);

        return $this->render('Donjon/player_card.html.twig', [
            'player' => $player,
            'messages' => $messages,
            'form' => $form->createView(),
        ]);
    }
}

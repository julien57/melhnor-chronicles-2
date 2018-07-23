<?php

namespace App\Controller\Game;

use App\Entity\Message;
use App\Entity\Player;
use App\Form\MessageType;
use App\Model\WriteMessageDTO;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class MessagingController extends Controller
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
     * @return Response
     *
     * @Route("/messages", name="game_messaging")
     * @Security("has_role('ROLE_PLAYER')")
     */
    public function messagesAction(): Response
    {
        $player = $this->getUser();
        $messages = $this->em->getRepository(Message::class)->getMessages($player);

        return $this->render('Game/messages.html.twig', ['messages' => $messages]);
    }

    /**
     * @param Request  $request
     * @param int|null $idRecipient
     *
     * @return RedirectResponse|Response
     *
     * @Route(
     *     "/envoyer-message/{idRecipient}",
     *     requirements={"idRecipient" = "\d+"},
     *     defaults={"idRecipient" = null},
     *     name="game_messaging_write"
     * )
     * @Security("has_role('ROLE_PLAYER')")
     */
    public function writeAction(Request $request, ?int $idRecipient, TranslatorInterface $translator)
    {
        if ($idRecipient) {
            $recipient = $this->em->getRepository(Player::class)->find($idRecipient);
            if (!$recipient) {
                $this->addFlash(
                    'notice-danger',
                    $translator->trans('messages.unavailable-player', [], 'game')
                );

                return $this->redirectToRoute('game_messaging_write');
            }
        }

        $writeMessageDTO = new WriteMessageDTO();
        $form = $this->createForm(MessageType::class, $writeMessageDTO, ['idRecipient' => $idRecipient]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sender = $this->getUser();

            $message = Message::createMessage($writeMessageDTO, $sender);

            $this->em->persist($message);
            $this->em->flush();

            $this->addFlash(
                'notice',
                $translator->trans('messages.message-sent', [], 'game')
            );

            return $this->redirectToRoute('game_messaging');
        }

        return $this->render('Game/write_message.html.twig', ['form' => $form->createView()]);
    }
}

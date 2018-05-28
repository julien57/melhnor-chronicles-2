<?php

namespace App\Controller\Game;

use App\Entity\Messaging;
use App\Form\MessageType;
use App\Model\WriteMessageDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/messages", name="messaging")
     */
    public function messagesAction()
    {
        $player = $this->getUser();

        $listMessages = $this->em->getRepository(Messaging::class)->findBy(
            ['sender' => $player],
            ['atDate' => 'desc'],
            30,
            0
        );

        return $this->render('Game/messages.html.twig', ['listMessages' => $listMessages]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     *
     * @Route("/envoyer-message", name="write_message")
     */
    public function writeMessageAction(Request $request)
    {
        $writeMessageDTO = new WriteMessageDTO();
        $form = $this->createForm(MessageType::class, $writeMessageDTO);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $recipient = $this->getUser();

            $message = Messaging::createMessage($writeMessageDTO, $recipient);

            $this->em->persist($message);
            $this->em->flush();

            $this->addFlash(
                'notice',
                'Message bien envoyé !'
            );

            return $this->redirectToRoute('messaging');
        }

        return $this->render('Game/writeMessage.html.twig', ['form' => $form->createView()]);
    }
}

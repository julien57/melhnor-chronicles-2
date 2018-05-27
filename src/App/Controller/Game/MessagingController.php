<?php

namespace App\Controller\Game;

use App\Form\MessageType;
use App\Model\WriteMessageDTO;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessagingController extends Controller
{
    /**
     * @Route("/messages", name="messaging")
     */
    public function messagesAction()
    {
        return $this->render('Game/messages.html.twig');
    }

    /**
     * @Route("/envoyer-message", name="write_message")
     */
    public function writeMessageAction(Request $request)
    {
        $writeMessageDTO = new WriteMessageDTO();
        $form = $this->createForm(MessageType::class, $writeMessageDTO);
        $form->handleRequest($request);

        if ($form->isValid()) {
            
        }

        return $this->render('Game/writeMessage.html.twig', ['form' => $form->createView()]);
    }
}
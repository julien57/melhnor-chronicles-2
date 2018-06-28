<?php

namespace App\Controller\Donjon;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends Controller
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
     * @Route("/messages", name="messagesDonjon")
     */
    public function messagesAction(): Response
    {
        $messages = $this->em->getRepository(Message::class)->findBy(
            ['recipient' => $this->getUser()],
            ['atDate' => 'desc'],
            10,
            0
        );

        return $this->render('Donjon/messages.html.twig', [
            'messages' => $messages,
        ]);
    }

    /**
     * @return Response
     *
     * @Route("/supprimer-messages", name="delete_messages")
     */
    public function deleteAction(Request $request)
    {
        $form = $this->get('form.factory')->create();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $messages = $this->em->getRepository(Message::class)->findByRecipient($this->getUser());

            foreach ($messages as $message) {
                $this->em->remove($message);
            }
            $this->em->flush();

            $this->addFlash('notice', 'Les messages ont bien été supprimés');

            return $this->redirectToRoute('messagesDonjon');
        }

        return $this->render('Donjon/delete_messages.html.twig', ['form' => $form->createView()]);
    }
}

<?php

namespace App\Controller\Donjon;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class MessagesController extends Controller
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
     * @return Response
     *
     * @Route("/messages", name="donjon_messages")
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
     * @Route("/supprimer-messages", name="donjon_delete_messages")
     */
    public function deleteAction(Request $request)
    {
        $form = $this->get('form.factory')->create();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messages = $this->em->getRepository(Message::class)->findByRecipient($this->getUser());

            foreach ($messages as $message) {
                $this->em->remove($message);
            }
            $this->em->flush();

            $this->addFlash('notice', $this->translator->trans('messages.deleted-messages'));

            return $this->redirectToRoute('donjon_messages');
        }

        return $this->render('Donjon/delete_messages.html.twig', ['form' => $form->createView()]);
    }
}

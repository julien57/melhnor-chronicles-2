<?php

namespace App\Controller\Game;

use App\Form\ContactType;
use App\Model\ContactAdminDTO;
use App\Service\Contact\ContactAdminManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends Controller
{
    /**
     * @var ContactAdminManager
     */
    private $contactAdminManager;

    public function __construct(ContactAdminManager $contactAdminManager)
    {
        $this->contactAdminManager = $contactAdminManager;
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        $contactAdminDTO = new ContactAdminDTO();
        $form = $this->createForm(ContactType::class, $contactAdminDTO);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->contactAdminManager->contactAdmin($contactAdminDTO);

            $this->addFlash('notice', 'Votre mail a bien été envoyé !');
            $this->redirectToRoute('contact');
        }

        return $this->render('Game/contact.html.twig', ['form' => $form->createView()]);
    }
}

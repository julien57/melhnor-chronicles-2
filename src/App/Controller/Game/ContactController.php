<?php

namespace App\Controller\Game;

use App\Form\ContactType;
use App\Model\ContactAdminDTO;
use App\Service\Contact\ContactAdminManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        $contactAdminDTO = new ContactAdminDTO();
        $form = $this->createForm(ContactType::class, $contactAdminDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactAdminManager->contactAdmin($contactAdminDTO);

            return new JsonResponse([
                'successMessage' => 'Formulaire envoyé ! nous vous recontacterons dans les plus brefs délais.',
                'errorMessage' => 'Le formulaire n\'a pas pu être envoyé.',
            ]);
        }

        return $this->render('Game/contact.html.twig', ['form' => $form->createView()]);
    }
}

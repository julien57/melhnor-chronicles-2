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
use Symfony\Component\Translation\TranslatorInterface;

class ContactController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/contact", name="game_contact")
     */
    public function contactAction(Request $request, ContactAdminManager $contactAdminManager, TranslatorInterface $translator): Response
    {
        $contactAdminDTO = new ContactAdminDTO();
        $form = $this->createForm(ContactType::class, $contactAdminDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactAdminManager->contactAdmin($contactAdminDTO);

            return new JsonResponse([
                'successMessage' => $translator->trans('messages.contact-success', [], 'game'),
                'errorMessage' => $translator->trans('messages.contact-error', [], 'game'),
            ]);
        }

        return $this->render('Game/contact.html.twig', ['form' => $form->createView()]);
    }
}

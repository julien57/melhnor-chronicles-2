<?php

namespace App\Controller\Game;

use App\Form\ContactType;
use App\Model\ContactAdminDTO;
use App\Service\Contact\ContactAdminManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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


        if ($request->isMethod('POST')) {

            $contact = $request->request->get('contact');

            $this->contactAdminManager->contactAdmin($contact);
            return $this->render('Game/contact.html.twig', ['form' => $form->createView()]);
        }

        return $this->render('Game/contact.html.twig', ['form' => $form->createView()]);
    }
}

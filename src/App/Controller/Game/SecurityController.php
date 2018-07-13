<?php

namespace App\Controller\Game;

use App\Form\LoginType;
use App\Form\RegistrationType;
use App\Model\CreatePlayerDTO;
use App\Service\Player\InitGamePlayerManager;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    /**
     * @param Request               $request
     * @param InitGamePlayerManager $initGamePlayerManager
     *
     * @return Response
     *
     * @Route("/inscription", name="game_security_registration")
     */
    public function registrationAction(Request $request, InitGamePlayerManager $initGamePlayerManager): Response
    {
        $createPlayerDTO = new CreatePlayerDTO();
        $form = $this->createForm(RegistrationType::class, $createPlayerDTO)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $initGamePlayerManager->initPlayerWithKingdom($createPlayerDTO);
        }

        return $this->render('Game/registration.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @return Response
     *
     * @Route("/connexion", name="game_security_login")
     */
    public function loginAction(): Response
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class, [
            '_username' => $lastUsername,
        ]);

        return $this->render('Game/connexion.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }

    /**
     * @throws Exception
     *
     * @Route("/logout", name="game_security_logout")
     */
    public function logoutAction(): Exception
    {
        throw new Exception('Impossible de se déconnecter !');
    }
}

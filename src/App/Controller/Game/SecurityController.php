<?php

namespace App\Controller\Game;

use App\Entity\Kingdom;
use App\Entity\Player;
use App\Form\LoginType;
use App\Form\RegistrationType;
use App\Model\CreatePlayerDTO;
use App\Service\Player\InitGamePlayerManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('Game/index.html.twig');
    }

    /**
     * @Route("/inscription", name="registration")
     */
    public function registrationAction(Request $request, InitGamePlayerManager $initGamePlayerManager)
    {
        $createPlayerDTO = new CreatePlayerDTO();

        $form = $this->createForm(RegistrationType::class, $createPlayerDTO)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $initGamePlayerManager->initPlayerWithKingdom($createPlayerDTO);
        }

        return $this->render('Game/registration.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function loginAction()
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
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('Impossible de se déconnecter !');
    }
}

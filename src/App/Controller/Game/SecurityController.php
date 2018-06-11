<?php

namespace App\Controller\Game;

use App\Entity\Kingdom;
use App\Entity\Player;
use App\Form\LoginType;
use App\Form\RegistrationType;
use App\Model\CreatePlayerDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
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
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('Game/index.html.twig');
    }

    /**
     * @Route("/inscription", name="registration")
     */
    public function registrationAction(Request $request)
    {
        $registrationDTO = new CreatePlayerDTO();
        $form = $this->createForm(RegistrationType::class, $registrationDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $kingdom = Kingdom::initKingdom($registrationDTO);
            $player = Player::initPlayer($registrationDTO, $kingdom);

            $this->em->persist($kingdom);
            $this->em->persist($player);
            $this->em->flush();

            $this->addFlash(
                'notice',
                'Bienvenue sur Melhnor, vous pouvez maintenant vous connecter !'
            );
            return $this->redirectToRoute('connection');
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

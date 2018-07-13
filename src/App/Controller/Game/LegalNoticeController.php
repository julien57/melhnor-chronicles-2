<?php

namespace App\Controller\Game;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalNoticeController extends Controller
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
     * @Route("/cgu", name="tos")
     */
    public function tosAction(): Response
    {
        return $this->render('Game/tos.html.twig');
    }

    /**
     * @return Response
     * @Route("/mentions-legales", name="legal-notice")
     */
    public function legalNoticeAction(): Response
    {
        return $this->render('Game/legal-notice.html.twig');
    }
}

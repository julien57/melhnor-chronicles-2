<?php

namespace App\Controller\Game;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalNoticeController extends Controller
{
    /**
     * @return Response
     *
     * @Route("/cgu", name="game_tos")
     */
    public function tosAction(): Response
    {
        return $this->render('Game/tos.html.twig');
    }

    /**
     * @return Response
     * @Route("/mentions-legales", name="game_legal_notice")
     */
    public function legalNoticeAction(): Response
    {
        return $this->render('Game/legal_notice.html.twig');
    }
}

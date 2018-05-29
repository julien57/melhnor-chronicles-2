<?php

namespace App\Controller\Game;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class LegalNoticeController extends Controller
{
    /**
     * @Route("/cgu", name="tos")
     */
    public function tosAction()
    {
        return $this->render('Game/tos.html.twig');
    }

    /**
     * @Route("/mentions-legales", name="legal-notice")
     */
    public function legalNoticeAction()
    {
        return $this->render('Game/legal-notice.html.twig');
    }
}
<?php

namespace App\Controller\Game;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class TosController extends Controller
{
    /**
     * @Route("/cgu", name="tos")
     */
    public function tosAction()
    {
        return $this->render('Game/tos.html.twig');
    }
}
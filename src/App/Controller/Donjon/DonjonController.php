<?php

namespace App\Controller\Donjon;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DonjonController extends Controller
{
    /**
     * @Route("/", name="donjon")
     */
    public function indexAction()
    {
        return $this->render('Donjon/index.html.twig');
    }
}

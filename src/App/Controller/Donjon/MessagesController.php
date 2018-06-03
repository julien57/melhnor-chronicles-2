<?php

namespace App\Controller\Donjon;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends Controller
{
    /**
     * @return Response
     *
     * @Route("/messages", name="messagesDonjon")
     */
    public function messagesAction()
    {
        return $this->render('Donjon/messages.html.twig');
    }
}
<?php

namespace App\Controller\Donjon;

use App\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DonjonController extends Controller
{
    /**
     * @param int $page
     * @return Response
     *
     * @Route("/dashboard/{page}", defaults={"page": 1}, requirements={"\d+"}, name="donjon")
     */
    public function indexAction(int $page): Response
    {
        $nbPlayersPerPage = $this->getParameter('nb_pagination_admin');
        $players = $this->getDoctrine()->getRepository(Player::class)->allPlayersWithPagination($page, $nbPlayersPerPage);

        $pagination = [
            'page' => $page,
            'pages_count' => ceil(count($players) / $nbPlayersPerPage),
            'route' => 'donjon',
            'route_params' => []
        ];

        return $this->render('Donjon/dashboard.html.twig', [
            'players' => $players,
            'pagination' => $pagination
        ]);
    }
}

<?php

namespace App\Controller\Donjon;

use App\Entity\Market;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DonjonController extends Controller
{
    /**
     * @param int $page
     *
     * @return Response
     *
     * @Route("/dashboard/{page}", defaults={"page": 1}, requirements={"\d+"}, name="donjon_index")
     */
    public function indexAction(int $page, EntityManagerInterface $em): Response
    {
        $nbPlayersPerPage = $this->getParameter('nb_pagination_admin');
        $players = $em->getRepository(Player::class)->allPlayersWithPagination($page, $nbPlayersPerPage);

        $pagination = [
            'page' => $page,
            'pages_count' => ceil(count($players) / $nbPlayersPerPage),
            'route' => 'donjon_index',
            'route_params' => [],
        ];

        $nbSales = $em->getRepository(Market::class)->countSales();

        return $this->render('Donjon/dashboard.html.twig', [
            'players' => $players,
            'pagination' => $pagination,
            'nbSales' => $nbSales,
        ]);
    }
}

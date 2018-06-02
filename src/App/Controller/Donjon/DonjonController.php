<?php

namespace App\Controller\Donjon;

use App\Entity\KingdomResource;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DonjonController extends Controller
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
     * @param int $page
     * @return Response
     *
     * @Route("/{page}", defaults={"page": 1}, requirements={"\d+"}, name="donjon")
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

    /**
     * * @param Request $request
     * @param int $id
     * @return RedirectResponse|Response
     *
     * @Route("supprimer-joueur/{id}", requirements={"\d+"}, name="deletePlayer")
     */
    public function deletePlayerAction(Request $request, int $id)
    {
        $player = $this->getDoctrine()->getRepository(Player::class)->find($id);

        if ($player === null) {
            throw new NotFoundHttpException('Le joueur avec l\'id '.$id.' n\'existe pas.');
        }

        if ($request->isMethod('POST')) {

            $kingdomResources = $this->em->getRepository(KingdomResource::class)->findByKingdom($player->getKingdom());

            foreach ($kingdomResources as $kingdomResource) {
                $this->em->remove($kingdomResource);
            }
            $this->em->remove($player);
            $this->em->flush();

            $this->addFlash('notice', 'Le joueur a bien été supprimé !');
            return $this->redirectToRoute('donjon');
        }

        return $this->render('Donjon/delete-player.html.twig', ['player' => $player]);
    }
}

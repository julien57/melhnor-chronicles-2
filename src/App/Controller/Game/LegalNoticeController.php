<?php

namespace App\Controller\Game;

use App\Entity\Building;
use App\Entity\BuildingResource;
use App\Entity\Region;
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

    /**
     * @return Response
     *
     * @Route("regles-du-jeu", name="gameRules")
     */
    public function gameRulesAction():Response
    {
        $buildings = $this->em->getRepository(Building::class)->findAll();

        $buildingsResources = $this->em->getRepository(BuildingResource::class)->getBuildingsWithResources();

        $regions = $this->em->getRepository(Region::class)->findAll();

        return $this->render('Game/game-rules.html.twig', [
            'buildings' => $buildings,
            'buildingsResources' => $buildingsResources,
            'regions' => $regions
        ]);
    }
}
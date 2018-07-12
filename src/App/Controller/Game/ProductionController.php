<?php

namespace App\Controller\Game;

use App\Entity\Player;
use App\Service\Production\ProductionPopulationManager;
use App\Service\Production\ProductionResourcesManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductionController extends Controller
{
    /**
     * @param ProductionResourcesManager  $resourcesManager
     * @param ProductionPopulationManager $populationManager
     *
     * @return RedirectResponse|Response
     *
     * @Route("/production", name="production")
     */
    public function productionAction(ProductionResourcesManager $resourcesManager, ProductionPopulationManager $populationManager)
    {
        $player = $this->getUser();

        if ($player->getActionPoints() < Player::ACTION_POINTS_FOR_PRODUCTION) {
            $this->addFlash('notice-danger', 'Pas assez de points d\'action !');

            return $this->redirectToRoute('kingdom');
        }

        $productionResult = $resourcesManager->processProduction();
        $resourcesConsumed = $populationManager->addPopulation($player);

        return $this->render('Game/production.html.twig', [
            'resultProduce' => $productionResult,
            'resourcesConsumed' => $resourcesConsumed,
        ]);
    }
}

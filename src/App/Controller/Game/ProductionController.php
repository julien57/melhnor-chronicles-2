<?php

namespace App\Controller\Game;

use App\Service\Production\ProductionPopulationManager;
use App\Service\Production\ProductionResourcesManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductionController extends Controller
{
    /**
     * @Route("/production", name="production")
     */
    public function productionAction(ProductionResourcesManager $resourcesManager, ProductionPopulationManager $populationManager)
    {
        $player = $this->getUser();

        if ($player->getActionPoints() < 10) {
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

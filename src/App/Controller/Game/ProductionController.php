<?php

namespace App\Controller\Game;

use App\Service\Production\ProductionPopulationManager;
use App\Service\Production\ProductionResourcesManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductionController extends Controller
{
    /**
     * @var ProductionResourcesManager
     */
    private $resourcesManager;

    /**
     * @var ProductionPopulationManager
     */
    private $populationManager;

    public function __construct(ProductionResourcesManager $resourcesManager, ProductionPopulationManager $populationManager)
    {
        $this->resourcesManager = $resourcesManager;
        $this->populationManager = $populationManager;
    }

    /**
     * @Route("/production", name="production")
     */
    public function productionAction()
    {
        $resultProduce = $this->resourcesManager->processProduction();

        $resourcesConsumed = $this->populationManager->addPopulation();

        return $this->render('Game/production.html.twig', [
            'resultProduce' => $resultProduce,
            'resourcesConsumed' => $resourcesConsumed,
        ]);
    }
}

<?php

namespace App\Controller\Game;

use App\Service\Production\ProductionResourcesManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductionController extends Controller
{
    /**
     * @var ProductionResourcesManager
     */
    private $productionResourcesManager;

    public function __construct(ProductionResourcesManager $productionResourcesManager)
    {
        $this->productionResourcesManager = $productionResourcesManager;
    }

    /**
     * @Route("/production", name="production")
     */
    public function productionAction()
    {
        $resultProduce = $this->productionResourcesManager->processProduction();

        return $this->render('Game/production.html.twig', [
            'resultProduce' => $resultProduce,
        ]);
    }
}

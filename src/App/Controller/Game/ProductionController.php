<?php

namespace App\Controller\Game;

use App\Service\Production\ProductionResourcesManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductionController extends Controller
{
    /**
     * @var ProductionResourcesManager
     */
    private $productionResourcesManager;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em, ProductionResourcesManager $productionResourcesManager)
    {
        $this->productionResourcesManager = $productionResourcesManager;
        $this->em = $em;
    }

    /**
     * @Route("/production", name="production")
     */
    public function productionAction()
    {
        $resultProduce = $this->productionResourcesManager->processProduction();

        var_dump($resultProduce); die();

        return $this->render('Game/production.html.twig');
    }
}

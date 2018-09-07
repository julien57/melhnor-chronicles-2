<?php

namespace App\Controller\Game;

use App\Entity\Player;
use App\Service\Production\ProductionPopulationManager;
use App\Service\Production\ProductionResourcesManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;

class ProductionController extends Controller
{
    /**
     * @param ProductionResourcesManager  $resourcesManager
     * @param ProductionPopulationManager $populationManager
     *
     * @return RedirectResponse|Response
     *
     * @Route("/production", name="game_production")
     * @Security("has_role('ROLE_PLAYER')")
     */
    public function productionAction(
        ProductionResourcesManager $resourcesManager,
        ProductionPopulationManager $populationManager,
        TranslatorInterface $translator
    ) {
        $player = $this->getUser();

        if ($player->getActionPoints() < Player::ACTION_POINTS_FOR_PRODUCTION) {
            $this->addFlash('notice-danger', $translator->trans('messages.unavailable-action-points', [], 'game'));

            return $this->redirectToRoute('game_kingdom');
        }

        $productionResult = $resourcesManager->processProduction($player);
        $resourcesConsumed = $populationManager->addPopulation($player);

        return $this->render('Game/production.html.twig', [
            'resultProduce' => $productionResult,
            'resourcesConsumed' => $resourcesConsumed,
        ]);
    }
}

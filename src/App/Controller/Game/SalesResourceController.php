<?php

namespace App\Controller\Game;

use App\Form\SaleResourceType;
use App\Model\SaleResourceDTO;
use App\Service\Market\SaleResourceManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalesResourceController extends Controller
{
    /**
     * @param Request $request
     * @param SaleResourceManager $saleResourceManager
     *
     * @return RedirectResponse|Response
     *
     * @Route("/vendre-ressource", name="game_sale_resource")
     */
    public function saleResourceAction(Request $request, SaleResourceManager $saleResourceManager)
    {
        $user = $this->getUser();
        $saleResourceDTO = new SaleResourceDTO();

        $form = $this->createForm(SaleResourceType::class, $saleResourceDTO, [
            'kingdom' => $user->getKingdom(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $isResourceAvailable = $saleResourceManager->isResourceAvailableToSale($saleResourceDTO, $user);

            if (!$isResourceAvailable) {
                $this->addFlash(
                    'notice-danger',
                    'La ressource sélectionnée n\'existe pas ou la quantité est trop élevée.'
                );

                return $this->redirectToRoute('game_sale_resource');
            }

            $saleResourceManager->processingSaleResource($isResourceAvailable, $saleResourceDTO);

            $this->addFlash('notice', 'Ressource ajoutée au marché !');

            return $this->redirectToRoute('game_market');
        }

        return $this->render('Game/add_resource.html.twig', ['form' => $form->createView()]);
    }
}

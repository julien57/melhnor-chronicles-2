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
use Symfony\Component\Translation\TranslatorInterface;

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
    public function saleResourceAction(Request $request, SaleResourceManager $saleResourceManager, TranslatorInterface $translator)
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
                    $translator->trans('messages.unavailable-resource-kingdom')
                );

                return $this->redirectToRoute('game_sale_resource');
            }

            $saleResourceManager->processingSaleResource($isResourceAvailable, $saleResourceDTO);

            $this->addFlash('notice', $translator->trans('messages.add-resource-market'));

            return $this->redirectToRoute('game_market');
        }

        return $this->render('Game/add_resource.html.twig', ['form' => $form->createView()]);
    }
}

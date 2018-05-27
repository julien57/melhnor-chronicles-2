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

class sellResourceController extends Controller
{
    /**
     * @var SaleResourceManager
     */
    private $saleResourceManager;

    public function __construct(SaleResourceManager $saleResourceManager)
    {
        $this->saleResourceManager = $saleResourceManager;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     *
     * @Route("/vendre-ressource", name="saleResource")
     */
    public function addResourceAction(Request $request)
    {
        $saleResourceDTO = new SaleResourceDTO();

        $form = $this->createForm(SaleResourceType::class, $saleResourceDTO);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $isResourceAvailable = $this->saleResourceManager->isResourceAvailableToSale($saleResourceDTO);

            if (!$isResourceAvailable) {

                $this->addFlash(
                    'notice-danger',
                    'La ressource sélectionnée n\'existe pas ou la quantité est trop élevée.'
                );
                return $this->redirectToRoute('saleResource');
            }

            $this->saleResourceManager->processingSaleResource($isResourceAvailable, $saleResourceDTO);

            $this->addFlash('notice', 'Ressource ajoutée au marché !');
            return $this->redirectToRoute('market');
        }

        return $this->render('Game/addResource.html.twig', ['form' => $form->createView()]);
    }
}
<?php

namespace App\Controller\Game;

use App\Entity\Market;
use App\Service\Market\PurchaseResourceManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MarketController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PurchaseResourceManager
     */
    private $purchaseResourceManager;

    public function __construct(EntityManagerInterface $em, PurchaseResourceManager $purchaseResourceManager)
    {
        $this->em = $em;
        $this->purchaseResourceManager = $purchaseResourceManager;
    }

    /**
     * @return Response
     *
     * @Route("/commerce", name="market")
     */
    public function marketAction(): Response
    {
        /** @var Market $market */
        $resourcesForSale = $this->em->getRepository(Market::class)->getKingdomResources();

        return $this->render('Game/market.html.twig', ['ressourcesForSale' => $resourcesForSale]);
    }

    /**
     * @param int $id
     *
     * @return RedirectResponse
     *
     * @Route("/achat-ressource/{resource_id}", name="buyResource")
     * @ParamConverter("resource", options={"mapping": {"resource_id": "id"}})
     */
    public function buyAction(Market $resource): RedirectResponse
    {
        $buyer = $this->getUser();
        $resourceMarket = $this->em->getRepository(Market::class)->find($resource);

        if (!$resourceMarket) {
            $this->addFlash(
                'notice-danger',
                'La ressource sélectionnée n\'est plus disponible au marché.'
            );

            return $this->redirectToRoute('market');
        }

        $isPossibleToBuy = $this->purchaseResourceManager->canPlayerBuyResource($resourceMarket, $buyer);

        if (!$isPossibleToBuy) {
            $this->addFlash(
                'notice-danger',
                'Vous n\'avez pas assez d\'or pour acheter cette quantité de resource.'
            );

            return $this->redirectToRoute('market');
        }

        // Process at transaction
        $this->purchaseResourceManager->processTransaction($resourceMarket, $buyer);

        // When the the transaction is perform, remove the advert
        $this->em->remove($resourceMarket);
        $this->em->flush();

        $resourceName = $resourceMarket->getKingdomResource()->getResource()->getName();

        $this->addFlash(
            'notice',
            'Vous venez d\'acheter une quantité de '.$resourceMarket->getQuantity().' de '.$resourceName
        );

        return $this->redirectToRoute('market');
    }
}

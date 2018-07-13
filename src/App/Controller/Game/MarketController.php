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
     * @Route("/commerce", name="game_market")
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
     * @Route("/achat-ressource/{id}", name="game_market_buy")
     */
    public function buyAction(Market $market): RedirectResponse
    {
        $buyer = $this->getUser();

        if (!$market) {
            $this->addFlash(
                'notice-danger',
                'La ressource sélectionnée n\'est plus disponible au marché.'
            );

            return $this->redirectToRoute('game_market');
        }

        $isPossibleToBuy = $this->purchaseResourceManager->canPlayerBuyResource($market, $buyer);

        if (!$isPossibleToBuy) {
            $this->addFlash(
                'notice-danger',
                'Vous n\'avez pas assez d\'or pour acheter cette quantité de resource.'
            );

            return $this->redirectToRoute('game_market');
        }

        // Process at transaction
        $this->purchaseResourceManager->processTransaction($market, $buyer);

        // When the the transaction is perform, remove the advert
        $this->em->remove($market);
        $this->em->flush();

        $resourceName = $market->getKingdomResource()->getResource()->getName();

        $this->addFlash(
            'notice',
            'Vous venez d\'acheter une quantité de '.$market->getQuantity().' de '.$resourceName
        );

        return $this->redirectToRoute('game_market');
    }
}

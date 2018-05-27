<?php

namespace App\Controller\Game;

use App\Entity\Market;
use App\Service\Market\BuyResourceManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class MarketController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var BuyResourceManager
     */
    private $buyResourceManager;

    public function __construct(EntityManagerInterface $em, BuyResourceManager $buyResourceManager)
    {
        $this->em = $em;
        $this->buyResourceManager = $buyResourceManager;
    }

    /**
     * @Route("/commerce", name="market")
     */
    public function marketAction()
    {
        /** @var Market $market */
        $resourcesForSale = $this->em->getRepository(Market::class)->findAll();

        return $this->render('Game/market.html.twig', ['ressourcesForSale' => $resourcesForSale]);
    }

    /**
     * @param int $id
     *
     * @Route("/achat-ressource/{id}", name="buyResource")
     */
    public function buyAction(int $id): RedirectResponse
    {
        $resourceMarket = $this->em->getRepository(Market::class)->find($id);

        if (!$resourceMarket) {
            $this->addFlash(
                'notice-danger',
                'La ressource sélectionnée n\'est plus disponible au marché.'
            );
            return $this->redirectToRoute('market');
        }

        $isPossibleToBuy = $this->buyResourceManager->isPossibleToBuy($resourceMarket);

        if (!$isPossibleToBuy) {
            $this->addFlash(
                'notice-danger',
                'Vous n\'avez pas assez d\'or pour acheter cette quantité de resource.'
            );
            return $this->redirectToRoute('market');
        }

        // Process at transaction
        $this->buyResourceManager->processingBuyResource($resourceMarket);

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

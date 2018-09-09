<?php

namespace App\Controller\Game;

use App\Entity\Market;
use App\Entity\Message;
use App\Entity\Player;
use App\Form\SaleResourceType;
use App\Model\SaleResourceDTO;
use App\Service\Market\PurchaseResourceManager;
use App\Service\Market\SaleResourceManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

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

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, PurchaseResourceManager $purchaseResourceManager, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->purchaseResourceManager = $purchaseResourceManager;
        $this->translator = $translator;
    }

    /**
     * @return Response
     *
     * @Route("/commerce", name="game_market")
     * @Security("has_role('ROLE_PLAYER')")
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
     * @Security("has_role('ROLE_PLAYER')")
     */
    public function buyAction(Market $market): RedirectResponse
    {
        $buyer = $this->getUser();

        if (!$market) {
            $this->addFlash(
                'notice-danger',
                $this->translator->trans('messages.unavailable-resource', [], 'game')
            );

            return $this->redirectToRoute('game_market');
        }

        $isPossibleToBuy = $this->purchaseResourceManager->canPlayerBuyResource($market, $buyer);

        if (!$isPossibleToBuy) {
            $this->addFlash(
                'notice-danger',
                $this->translator->trans('messages.unavailable-gold', [], 'game')
            );

            return $this->redirectToRoute('game_market');
        }

        // Process at transaction
        $this->purchaseResourceManager->processTransaction($market, $buyer);

        $kingdomSaler = $market->getKingdomResource()->getKingdom();
        $saler = $this->em->getRepository(Player::class)->findByKingdom($kingdomSaler);
        $messageSaler = Message::createAutomaticMessage($market, $saler, $buyer);

        // When the the transaction is perform, remove the advert
        $this->em->remove($market);
        $this->em->persist($messageSaler);
        $this->em->flush();

        $resourceName = $market->getKingdomResource()->getResource()->getName();

        $this->addFlash(
            'notice',
            $this->translator->trans('messages.buy-resource', [
                '%quantity%' => $market->getQuantity(),
                '%name%' => $resourceName,
            ], 'game')
        );

        return $this->redirectToRoute('game_market');
    }

    /**
     * @param Request             $request
     * @param SaleResourceManager $saleResourceManager
     *
     * @return RedirectResponse|Response
     *
     * @Route("/vendre-ressource", name="game_market_sale")
     * @Security("has_role('ROLE_PLAYER')")
     */
    public function saleAction(Request $request, SaleResourceManager $saleResourceManager, TranslatorInterface $translator)
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
                    $translator->trans('messages.unavailable-resource-kingdom', [], 'game')
                );

                return $this->redirectToRoute('game_market_sale');
            }

            $saleResourceManager->processingSaleResource($isResourceAvailable, $saleResourceDTO);

            $this->addFlash('notice', $translator->trans('messages.add-resource-market', [], 'game'));

            return $this->redirectToRoute('game_market');
        }

        return $this->render('Game/add_resource.html.twig', ['form' => $form->createView()]);
    }
}

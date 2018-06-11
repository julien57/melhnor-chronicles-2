<?php

namespace App\Service\Leveling;

use App\Entity\Kingdom;
use App\Entity\KingdomBuilding;
use App\Entity\KingdomResource;
use App\Entity\Resource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class LevelingBuildingManager
{
    /**
     * @var int
     */
    private $level;

    /**
     * @var array|null
     */
    private $building = null;

    /**
     * Array contains rules for buildings from building_leveling_rules.yml
     *
     * @var array
     */
    private $buildingsRules;

    /**
     * @var int
     */
    private $goldRequired;

    /**
     * @var int|null
     */
    private $woodRequired;

    /**
     * @var int|null
     */
    private $stoneRequired;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var Router
     */
    private $router;

    public function __construct(
        $buildingsRules,
        EntityManagerInterface $em,
        SessionInterface $session,
        RouterInterface $router
    ) {
        $this->buildingsRules = $buildingsRules;
        $this->em = $em;
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @param $kingdomBuildingsForm
     */
    public function searchLevelModified($kingdomBuildingsForm)
    {
        /** @var KingdomBuilding $kingdomBuilding */
        foreach ($kingdomBuildingsForm as $kingdomBuilding) {
            $modifiedBuilding = $this->em->getRepository(KingdomBuilding::class)->findLevelBuildingUp(
                $kingdomBuilding->getKingdom()->getId(),
                $kingdomBuilding->getBuilding()->getId(),
                $kingdomBuilding->getLevel()
            );

            if (!is_null($modifiedBuilding)) {
                $resourcesRequired = $this->processingResourcesKingdom($modifiedBuilding);

                if (!$resourcesRequired) {
                    $this->session->getFlashBag()->add('notice-danger', 'Ressources manquantes !');
                    return new RedirectResponse($this->router->generate('kingdom'));
                }
                $this->session->getFlashBag()->add('notice', 'Niveau du bâtiment augmenté !');
                return new RedirectResponse($this->router->generate('kingdom'));
            }
        }
    }

    /**
     * @param $modifiedBuilding
     *
     * @return bool
     */
    private function processingResourcesKingdom(KingdomBuilding $modifiedBuilding): bool
    {
        $this->building = $this->buildingsRules[$modifiedBuilding->getBuilding()->getId()];
        $this->level = $modifiedBuilding->getLevel();
        $this->requiredGoldAmount();
        $this->requiredResourcesAmount();
        /** @var Kingdom $kingdomPlayer */
        $kingdomPlayer = $modifiedBuilding->getKingdom();

        // Gold Process
        $goldPlayer = $kingdomPlayer->getGold();

        $goldResult = $goldPlayer - $this->goldRequired;

        if ($goldResult < 0) {
            return false;
        } else {
            $kingdomPlayer->setGold($goldResult);
        }

        // Resources process (wood & stone)
        $kingdomResources = $this->em->getRepository(KingdomResource::class)->findByKingdom($kingdomPlayer);

        /** @var KingdomResource $kingdomResource */
        foreach ($kingdomResources as $kingdomResource) {
            if ($kingdomResource->getResource()->getId() === Resource::WOOD_ID) {
                $woodResult = $kingdomResource->getQuantity() - $this->woodRequired;

                if ($woodResult < 0) {
                    return false;
                }

                $kingdomResource->setQuantity($woodResult);
            }

            if ($kingdomResource->getResource()->getId() === Resource::STONE_ID) {
                $stoneResult = $kingdomResource->getQuantity() - $this->stoneRequired;

                if ($stoneResult < 0) {
                    return false;
                }

                $kingdomResource->setQuantity($stoneResult);
            }
        }

        $this->em->flush();

        return true;
    }

    private function requiredGoldAmount()
    {
        $nbGold = ($this->level * $this->level) * $this->building['gold'];
        $this->goldRequired += $nbGold;
    }

    private function requiredResourcesAmount()
    {
        $nbWood = ($this->level * $this->level) * $this->building['resources']['24']['quantity'];

        $this->woodRequired += $nbWood;

        if (array_key_exists(Resource::STONE_ID, $this->building['resources'])) {

            $nbStone = ($this->level * $this->level) * $this->building['resources']['23']['quantity'];
            $this->stoneRequired += $nbStone;
        }
    }
}

<?php

namespace App\Doctrine;

use App\Entity\Kingdom;
use App\Service\Player\InitGamePlayerManager;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class PlayerCreationListener
{
    /**
     * @var InitGamePlayerManager
     */
    private $initGamePlayerManager;

    public function __construct(InitGamePlayerManager $initGamePlayerManager)
    {
        $this->initGamePlayerManager = $initGamePlayerManager;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Kingdom) {
            return;
        }

        $this->initGamePlayerManager->initKingdomResources($entity);
    }
}
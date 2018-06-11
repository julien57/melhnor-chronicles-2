<?php

namespace App\Doctrine;

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
        dump($entity); die();
        $this->initGamePlayerManager->createKingdom($entity);
    }
}
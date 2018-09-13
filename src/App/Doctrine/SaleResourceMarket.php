<?php

namespace App\Doctrine;

use App\Entity\Market;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class SaleResourceMarket
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Market) {
            return;
        }

        $this->initGamePlayerManager->initKingdomResources($entity);
    }
}

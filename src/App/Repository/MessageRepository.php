<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\ORM\EntityRepository;

class MessageRepository extends EntityRepository
{
    public function getMessages(Player $player)
    {
        $query = $this->_em->createQuery(
            'SELECT m, s FROM AppBundle:Message m 
             JOIN m.sender s
             WHERE m.recipient = :recipient
             ORDER BY m.atDate DESC'
        );
        $query->setParameter('recipient', $player);

        return $query->getResult();
    }
}

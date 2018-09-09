<?php
namespace App\Doctrine;
use App\Entity\Kingdom;
use App\Entity\Player;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
class ActivityPlayerListener
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }
    public function postPersist(LifecycleEventArgs $args)
    {
        /** @var Player $user */
        $user = $this->tokenStorage->getToken()->getUser();

        if (!is_string($user)) {
            $date = new \DateTime();
            $user->setLastConnection($date);
            $this->em->flush();
        }
    }
}
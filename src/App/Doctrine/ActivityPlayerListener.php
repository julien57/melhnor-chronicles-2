<?php
namespace App\Doctrine;
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

    public function postUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if($object instanceof Player) {
            return;
        }
        /** @var Player $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $user->setLastConnection(new \DateTime());
        $this->em->flush();
    }
}
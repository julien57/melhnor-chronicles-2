<?php

namespace App\Entity;

use App\Model\WriteMessageDTO;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Player")
     */
    private $recipient;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Player")
     */
    private $sender;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var \DateTime
     * @ORM\Column(name="at_date", type="datetime")
     */
    private $atDate;

    public function __construct()
    {
        $this->atDate = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return Player
     */
    public function getRecipient(): Player
    {
        return $this->recipient;
    }

    /**
     * @param Player $recipient
     */
    public function setRecipient(Player $recipient): void
    {
        $this->recipient = $recipient;
    }

    /**
     * @return Player
     */
    public function getSender(): Player
    {
        return $this->sender;
    }

    /**
     * @param Player $sender
     */
    public function setSender(Player $sender): void
    {
        $this->sender = $sender;
    }

    /**
     * @return \DateTime
     */
    public function getAtDate(): \DateTime
    {
        return $this->atDate;
    }

    /**
     * @param \DateTime $atDate
     */
    public function setAtDate(\DateTime $atDate): void
    {
        $this->atDate = $atDate;
    }

    public static function createMessage(WriteMessageDTO $messageDTO, Player $sender)
    {
        $messaging = new self();

        $messaging->recipient = $messageDTO->getRecipient();
        $messaging->sender = $sender;
        $messaging->subject = $messageDTO->getSubject();
        $messaging->message = $messageDTO->getMessage();

        return $messaging;
    }

    public static function createAutomaticMessage(Market $market, Player $saler, Player $buyer)
    {
        $resourceName = $market->getKingdomResource()->getResource()->getName();
        $quantity = $market->getQuantity();

        $messaging = new self();

        $messaging->recipient = $saler;
        $messaging->setSubject('Ressource vendu au marché !');
        $messaging->sender = $buyer;
        $messaging->message = 'Mon seigneur, je viens d\'acheter une quantité de '.$quantity.' de '.$resourceName.' provenant de votre royaume.';

        return $messaging;
    }

    public static function messageForWinner(Player $chiefArmy, Player $player, Event $event)
    {
        $messaging = new self();

        $messaging->sender = $chiefArmy;
        $messaging->recipient = $player;
        $messaging->setSubject($event->getName());
        $messaging->message = 'Je nous félicite mon seigneur, '.$event->getName().' est enfin mort et nous avons remporté la plus grosse partie du butin : 10 000 pièces d\'or !';

        return $messaging;
    }

    public static function messageBattleDefender(Player $chiefArmy, Player $playerAttacker, Player $playerDefender, int $populationDead)
    {
        $messaging = new self();

        $messaging->sender = $chiefArmy;
        $messaging->recipient = $playerDefender;
        $messaging->setSubject('Attaque de '.$playerAttacker->getUsername());
        $message = 'Mon roi, le '.$playerAttacker->getKingdom()->getName().' nous a attaqué ! '. $populationDead .' villageois morts. Que pensez-vous d\'une riposte ?';
        $messaging->setMessage($message);

        return $messaging;
    }
}

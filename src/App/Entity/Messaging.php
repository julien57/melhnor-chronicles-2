<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="messaging")
 * @ORM\Entity(repositoryClass="App\Repository\MessagingRepository")
 */
class Messaging
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
}

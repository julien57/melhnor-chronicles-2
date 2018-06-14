<?php

namespace App\Model;

use App\Entity\Player;

class WriteMessageDTO
{
    /**
     * @var string|null
     */
    private $subject;

    /**
     * @var string|null
     */
    private $message;

    /**
     * @var Player|null;
     */
    private $sender;

    /**
     * @return null|string
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param null|string $subject
     */
    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return null|string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param null|string $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return Player|null
     */
    public function getSender(): ?Player
    {
        return $this->sender;
    }

    /**
     * @param Player|null $sender
     */
    public function setSender(?Player $sender): void
    {
        $this->sender = $sender;
    }
}

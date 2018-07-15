<?php

namespace App\Model;

use App\Entity\Player;
use Symfony\Component\Validator\Constraints as Assert;

class WriteMessageDTO
{
    /**
     * @var string|null
     * @Assert\Type("string")
     */
    private $subject;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    private $message;

    /**
     * @var Player|null;
     */
    private $recipient;

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
    public function getRecipient(): ?Player
    {
        return $this->recipient;
    }

    /**
     * @param Player|null $recipient
     */
    public function setRecipient(?Player $recipient): void
    {
        $this->recipient = $recipient;
    }
}

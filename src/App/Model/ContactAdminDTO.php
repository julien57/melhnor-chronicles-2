<?php

namespace App\Model;

class ContactAdminDTO
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $mail;

    /**
     * @var string|null
     */
    private $subject;

    /**
     * @var string|null
     */
    private $body;

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * @param null|string $mail
     */
    public function setMail(?string $mail): void
    {
        $this->mail = $mail;
    }

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
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param null|string $body
     */
    public function setBody(?string $body): void
    {
        $this->body = $body;
    }
}

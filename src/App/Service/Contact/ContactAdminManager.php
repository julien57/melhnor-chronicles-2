<?php

namespace App\Service\Contact;

use App\Model\ContactAdminDTO;

class ContactAdminManager
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function contactAdmin(ContactAdminDTO $contactAdminDTO)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($contactAdminDTO->getSubject())
            ->setFrom($contactAdminDTO->getMail())
            ->setTo('julien.montel@sfr.fr')
            ->setBody($contactAdminDTO->getBody())
        ;

        $this->mailer->send($message);
    }
}

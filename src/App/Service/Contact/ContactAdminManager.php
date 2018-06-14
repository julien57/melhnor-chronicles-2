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

    /**
     * @param $contact
     */
    public function contactAdmin(ContactAdminDTO $contact): void
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($contact->getSubject())
            ->setFrom($contact->getMail())
            ->setTo('julien.montel@sfr.fr')
            ->setBody($contact->getBody())
        ;

        $this->mailer->send($message);
    }
}

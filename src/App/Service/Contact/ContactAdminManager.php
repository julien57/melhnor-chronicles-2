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

    public function contactAdmin($contact)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($contact['subject'])
            ->setFrom($contact['mail'])
            ->setTo('julien.montel@sfr.fr')
            ->setBody($contact['body'])
        ;

        $this->mailer->send($message);
    }
}

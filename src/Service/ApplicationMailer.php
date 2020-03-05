<?php
// src/Service/ApplicationMailer.php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ApplicationMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNotificationNewUser(User $user)
    {
        $message = new Email();

        $message
            ->from('hello@example.com')
            ->to($user->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Votre compte Annales ESIREM')
            ->html('<p>Votre compte Annales ESIREM a bien été créé. Merci. Connectez-vous sur <a href="https://esirem.nicolas-t.ovh/">ce lien</a>.</p>')
        ;

        $this->mailer->send($message);
    }
}

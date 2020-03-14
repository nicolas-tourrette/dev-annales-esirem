<?php
// src/Service/ApplicationMailer.php

namespace App\Service;

use App\Entity\User;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

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
        $message = new TemplatedEmail();

        $message
            ->from(new Address('esirem@nicolas-t.ovh', 'Annales ESIREM'))
            ->to(new Address($user->getEmail(), $user->getName()))
            //->cc('cc@example.com')
            ->bcc(new Address('postmaster@nicolas-t.ovh', 'Administrateur Annales ESIREM'))
            ->replyTo(new Address('postmaster@nicolas-t.ovh', 'No-Reply Annales ESIREM'))
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Création de votre compte Annales ESIREM')
            ->htmlTemplate('email/new_account.html.twig')
            ->context(array(
                'user' => $user
            ))
            //->html('<p>Votre compte Annales ESIREM a bien été créé. Merci. Connectez-vous sur <a href="https://esirem.nicolas-t.ovh/">ce lien</a>.</p>')
        ;

        $this->mailer->send($message);
    }
}

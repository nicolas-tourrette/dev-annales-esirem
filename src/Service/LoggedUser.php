<?php
// src/Service/LoggedUser.php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use App\Entity\User;
use App\Entity\Log;

use DateInterval;

class LoggedUser
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        // Get the User entity.
        $user = $event->getAuthenticationToken()->getUser();

        $log = new Log();
        $log->setLevel("success");
        $log->setMessage("L'utilisateur ".$user->getUsername()." s'est connecté avec succès depuis l'IP ".$this->getIp().".");

        // Persist the data to database.
        $this->em->persist($log);
        $this->em->flush();
    }

    private function getIp(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
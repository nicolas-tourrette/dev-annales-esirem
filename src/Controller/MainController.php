<?php

// src/Controller/MainController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class MainController extends AbstractController {
    
    /**
    * @Route("/", name="index")
    */
    public function index(){
        return $this->render('index.html.twig', array(
            'listNotifications' => array(/*
                ["message" => "Test nofitification 1", "date" => "2020-01-20", "icon" => "alert-triangle", "category" => "danger"],
                ["message" => "Test nofitification 2", "date" => "2020-02-15", "icon" => "badge-check", "category" => "info"]
            */)
        ));
    }

    public function notifications($limit){
        $listNotifications = array(
            ["message" => "Test nofitification 1", "date" => "2020-01-20", "icon" => "alert-triangle", "category" => "danger"],
            ["message" => "Test nofitification 2", "date" => "2020-02-15", "icon" => "badge-check", "category" => "info"]
        );

        return $this->render('notifications.html.twig', array(
            'listNotifications' => $listNotifications)
        );
    }

    /**
     * @Route("/informations", name="informations")
     */
    public function informations(){
         return $this->render('pages/informations.html.twig');
    }

    /**
     * @Route("/mentions-legales", name="mentionslegales")
     */
    public function mentionsLegales(){
         return $this->render('pages/informations.html.twig');
    }
}
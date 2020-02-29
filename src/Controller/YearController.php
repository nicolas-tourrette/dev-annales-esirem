<?php

// src/Controller/YearController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
* @Route("/{dpt}/{annee}")
*/
class YearController extends AbstractController {
    
    /**
    * @Route("/", name="accueilAnnee")
    */
    public function index($annee){
        return $this->render('index.html.twig', array(
            'listNotifications' => array(/*
                ["message" => "Test nofitification 1", "date" => "2020-01-20", "icon" => "alert-triangle", "category" => "danger"],
                ["message" => "Test nofitification 2", "date" => "2020-02-15", "icon" => "badge-check", "category" => "info"]
            */)
        ));
    }

    /**
    * @Route("/agenda", name="agendaAnnee")
    */
    public function agenda($annee){
        return $this->render('index.html.twig', array(
            'listNotifications' => array(/*
                ["message" => "Test nofitification 1", "date" => "2020-01-20", "icon" => "alert-triangle", "category" => "danger"],
                ["message" => "Test nofitification 2", "date" => "2020-02-15", "icon" => "badge-check", "category" => "info"]
            */)
        ));
    }
}
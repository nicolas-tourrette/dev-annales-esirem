<?php

// src/Controller/UserController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
    * @Route("/account")
    */
class UserController extends AbstractController {
    
    /**
    * @Route("/", name="account")
    */
    public function index(){
        return $this->render('app/acc_profil.html.twig');
    }
}
<?php

// src/Controller/MainController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class MainController extends AbstractController {
    
    /**
    * @Route("/", name="index")
    */
    public function index(Request $request){
        $request->getSession()->getFlashBag()->add('coronavirus', "");

        return $this->render('index.html.twig', array(
                'lastVersion' => $this->getLastVersion()
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
        return $this->render('pages/informations.html.twig', array(
            'listVersions' => $this->getVersions()
        ));
    }

    /**
     * @Route("/mentions-legales", name="mentionslegales")
     */
    public function mentionsLegales(){
         return $this->render('pages/mentionslegales.html.twig');
    }

    public function getLastVersion(){
        $jsonFile = "resources/version.json";
        if(file_exists($jsonFile)){
            $json = file_get_contents($jsonFile, false);
            $jsonDatas = json_decode($json, true);

            return $this->render('versions/last.html.twig', array(
                'lastVersion' => $jsonDatas[0]
            ));
        }

        return $this->render('versions/last.html.twig', array(
            'lastVersion' => null
        ));
    }

    public function getVersions(){
        $jsonFile = "resources/version.json";
        if(file_exists($jsonFile)){
            $json = file_get_contents($jsonFile, false);
            $jsonDatas = json_decode($json, true);

            return $jsonDatas;
        }
        return null;
    }
}
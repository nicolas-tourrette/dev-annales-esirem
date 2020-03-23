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
        $em = $this->getDoctrine()->getManager();
        $this->purgeNotifications();

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')){
            $username = $this->getUser()->getUsername();
            $usergroup = $this->getUser()->getUsergroup();
            $userRole = $this->getUser()->getRoles()[0];

            $listNotifications = $em->getRepository("App:Notification")->findMyNotifications($username, $usergroup, $userRole, $limit);
            $notifNumber = $em->getRepository("App:Notification")->findMyNotificationsNumber($username, $usergroup, $userRole);
        }
        else{
            $listNotifications = [];
            $notifNumber = 0;
        }
        

        return $this->render('notifications.html.twig', array(
            'listNotifications' => $listNotifications,
            'notifNumber' => $notifNumber
        ));
    }

    private function purgeNotifications(){
        $em = $this->getDoctrine()->getManager();

        $date = new \Datetime("30 days ago");
        $listNotifs = $em->getRepository("App:Notification")->getNotificationsOlderThan($date);

        foreach($listNotifs as $notif){
            $em->remove($notif);
        }
        $em->flush(); 
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
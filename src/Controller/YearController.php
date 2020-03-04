<?php

// src/Controller/YearController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

/**
* @Route("/{dpt}/{annee}")
*/
class YearController extends AbstractController {
    
    /**
    * @Route("/", name="accueilAnnee")
    */
    public function index(){
        return $this->render('index.html.twig');
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

    /**
    * @Route("/matieres", name="matieresAnnee")
    */
    public function matieresList(Request $request, $dpt, $annee){
        if($request->isMethod('POST')){
            return $this->redirectToRoute('matiereDetails', array(
                'dpt' => $dpt,
                'annee' => $annee,
                'id' => $request->request->get('id')
            ));
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('App:Matiere');

        $listMatieres = $repository->getMatiereByDptYear($dpt, $annee);

        return $this->render('app/matieres.html.twig', array(
            'listMatieres' => $listMatieres
        ));
    }

    /**
    * @Route("/matieres/{id}", name="matiereDetails")
    */
    public function matiereDisplay(Request $request,  $dpt, $annee, $id){
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('App:Matiere');

        $matiere = $repository->getMatiere($dpt, $annee, $id);

        dump($matiere);

        if($matiere == []){
            throw new NotFoundHttpException("Cette matière n'a pas été trouvée.");
        }

        return $this->render('app/matiereDetails.html.twig', array(
            'matiere' => $matiere
        ));
    }
}
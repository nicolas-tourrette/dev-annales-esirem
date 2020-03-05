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
    public function agenda(Request $request, $annee){

        $request->getSession()->getFlashBag()->add('warning', 'Cette fonction n\'est pas disponible.');
        return $this->render('index.html.twig');
    }

    /**
    * @Route("/informations", name="infosAnnee")
    */
    public function informations(Request $request, $annee){

        $request->getSession()->getFlashBag()->add('warning', 'Cette fonction n\'est pas disponible. Reportez-vous à un groupe Facebook ou Messenger.');
        return $this->render('index.html.twig');
    }

    /**
    * @Route("/matieres", name="matieresAnnee")
    */
    public function matieresList(Request $request, $dpt, $annee){
        if($request->isMethod('POST') && $request->request->get('id') != null){
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
    * @Route("/matiere/{id}", name="matiereDetails")
    */
    public function matiereDisplay(Request $request,  $dpt, $annee, $id){
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('App:Matiere');

        $matiere = $repository->getMatiere($dpt, $annee, $id);

        if($matiere == []){
            throw new NotFoundHttpException("Cette matière n'a pas été trouvée.");
        }

        $listCours = $em->getRepository("App:Cours")->findBy(array(
            'matiere' => $id
        ), array(
            'date' => 'DESC'
        ));

        $listAnnales = $em->getRepository("App:Annale")->findBy(array(
            'matiere' => $id
        ), array(
            'date' => 'DESC'
        ));

        return $this->render('app/matiereDetails.html.twig', array(
            'matiere' => $matiere[0],
            'listCours' => $listCours,
            'listAnnales' => $listAnnales
        ));
    }
}
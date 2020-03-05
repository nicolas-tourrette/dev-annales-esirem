<?php

// src/Controller/DataController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Matiere;
use App\Entity\Cours;

use App\Form\MatiereType;
use App\Form\CoursType;

/**
 * @Route("/datas")
 */
class DataController extends AbstractController {
    /**
    * @Route("/matiere/add", name="matiereAjout")
    */
    public function addMatiere(Request $request)
	{
        $em = $this->getDoctrine()->getManager();
		$matiere = new Matiere();

        $form = $this->get('form.factory')->create(MatiereType::class, $matiere);
        
        if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($matiere);
                $em->flush();
                
                return $this->redirectToRoute('matiereDetails', array(
                    'id' => $matiere->getId(),
                    'dpt' => $matiere->getDepartement(),
                    'annee' => $matiere->getAnnee()
                ));
            }
        }

		return $this->render('app/forms/form_matiere.html.twig', array(
			'form'  => $form->createView(),
		));
    }
    
    /**
    * @Route("/cours/add", name="coursAjout")
    */
    public function addCours(Request $request)
	{
        $em = $this->getDoctrine()->getManager();
		$cours = new Cours();

        $form = $this->get('form.factory')->create(CoursType::class, $cours);
        
        if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($cours);
                $em->flush();
                
                return $this->redirectToRoute('matiereDetails', array(
                    'id' => $cours->getMatiere()->getId(),
                    'dpt' => $cours->getMatiere()->getDepartement(),
                    'annee' => $cours->getMatiere()->getAnnee()
                ));
            }
        }

		return $this->render('app/forms/form_cours.html.twig', array(
			'form'  => $form->createView(),
		));
	}
}
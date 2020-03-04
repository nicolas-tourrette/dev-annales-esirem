<?php

// src/Controller/DataController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Matiere;

use App\Form\MatiereType;

/**
 * @Route("/datas")
 */
class DataController extends AbstractController {
    /**
    * @Route("/matieres/add", name="matiereAjout")
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
}
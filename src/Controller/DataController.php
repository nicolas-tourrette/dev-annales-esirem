<?php

// src/Controller/DataController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Matiere;
use App\Entity\Cours;
use App\Entity\Annale;
use App\Entity\Notification;

use App\Form\MatiereType;
use App\Form\CoursType;
use App\Form\AnnaleType;

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
                
                $request->getSession()->getFlashBag()->add('info', 'La matière a bien été ajoutée.');
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
                $notif = new Notification();
                $notif->setMessage($this->getUser()->getName()." a ajouté le cours \"".$cours->getSubject()."\" de la matière ".$cours->getMatiere()->getNom().".");
                $notif->setCategory("info");
                $notif->setIcon("graduation-cap");
                $notif->setRecipient($cours->getMatiere()->getDepartement().substr($cours->getMatiere()->getAnnee(), 0, 1));

                $em->persist($cours);
                $em->persist($notif);
                $em->flush();
                
                $request->getSession()->getFlashBag()->add('info', 'Le cours bien été ajouté.');
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
    
    /**
    * @Route("/annale/add", name="annaleAjout")
    */
    public function addAnnale(Request $request)
	{
        $em = $this->getDoctrine()->getManager();
		$annale = new Annale();

        $form = $this->get('form.factory')->create(AnnaleType::class, $annale);
        
        if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
                $notif = new Notification();
                $notif->setMessage($this->getUser()->getName()." a ajouté l'annale \"".$annale->getSubject()."\" de la matière ".$annale->getMatiere()->getNom().".");
                $notif->setCategory("info");
                $notif->setIcon("file-text");
                $notif->setRecipient($annale->getMatiere()->getDepartement().substr($annale->getMatiere()->getAnnee(), 0, 1));

                $em->persist($annale);
                $em->persist($notif);
                $em->flush();
                
                $request->getSession()->getFlashBag()->add('info', 'L\'annale a bien été ajoutée.');
                return $this->redirectToRoute('matiereDetails', array(
                    'id' => $annale->getMatiere()->getId(),
                    'dpt' => $annale->getMatiere()->getDepartement(),
                    'annee' => $annale->getMatiere()->getAnnee()
                ));
            }
        }

		return $this->render('app/forms/form_annale.html.twig', array(
			'form'  => $form->createView(),
		));
    }
    
    /**
     * @Route("/search", name="search")
     */
    public function resourceSearch(Request $request){
        $em = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
            $listOfCourses = $em->getRepository("App:Cours")->findByKeyword($request->request->get('search'));
            $listOfAnnales = $em->getRepository("App:Annale")->findByKeyword($request->request->get('search'));
            if($listOfCourses != [] || $listOfAnnales != []){
                return $this->render('app/search.html.twig', array(
                    'keyword' => $request->request->get('search'),
                    'listOfCourses'  => $listOfCourses,
                    'listOfAnnales'  => $listOfAnnales
                ));
            }
            $request->getSession()->getFlashBag()->add('info', 'Cette recherche n\'a rien donné.');
            return $this->redirectToRoute('index');
        }

        $request->getSession()->getFlashBag()->add('info', 'Vous n\'avez pas effectué de recherche.');
        return $this->redirectToRoute('index');
    }
}
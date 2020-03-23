<?php

// src/Controller/DataController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use App\Entity\Matiere;
use App\Entity\Cours;
use App\Entity\Annale;
use App\Entity\Notification;
use App\Entity\Log;

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

                $log = new Log();
                $log->setLevel("success");
                $log->setMessage("La matière ".$matiere->getNom()." a été créée avec succès par l'utilisateur ".$this->getUser()->getUsername().".");
                $em->persist($log);

                $em->flush();
                
                $request->getSession()->getFlashBag()->add('info', 'La matière a bien été ajoutée.');
                return $this->redirectToRoute('matiereDetails', array(
                    'id' => $matiere->getId(),
                    'dpt' => $matiere->getDepartement(),
                    'annee' => $matiere->getAnnee()
                ));
            }
            else{
                $log = new Log();
                $log->setLevel("danger");
                $log->setMessage("Échec de la création de la matière ".$matiere->getNom()." par l'utilisateur ".$this->getUser()->getUsername().".");
                $em->persist($log);
                $em->flush();
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
                $notif->setCategory("success");
                $notif->setIcon("graduation-cap");
                $notif->setRecipient($cours->getMatiere()->getDepartement().substr($cours->getMatiere()->getAnnee(), 0, 1));

                $em->persist($cours);
                $em->persist($notif);

                $log = new Log();
                $log->setLevel("success");
                $log->setMessage("Le cours ".$cours->getSubject()." a été créé avec succès par l'utilisateur ".$this->getUser()->getUsername().".");
                $em->persist($log);

                $em->flush();
                
                $request->getSession()->getFlashBag()->add('info', 'Le cours bien été ajouté.');
                return $this->redirectToRoute('matiereDetails', array(
                    'id' => $cours->getMatiere()->getId(),
                    'dpt' => $cours->getMatiere()->getDepartement(),
                    'annee' => $cours->getMatiere()->getAnnee()
                ));
            }
            else{
                $log = new Log();
                $log->setLevel("danger");
                $log->setMessage("Échec de la création du cours ".$cours->getSubject()." par l'utilisateur ".$this->getUser()->getUsername().".");
                $em->persist($log);
                $em->flush();
            }
        }

		return $this->render('app/forms/form_cours.html.twig', array(
			'form'  => $form->createView(),
		));
    }

    /**
    * @Route("/cours/edit/{id}", name="coursEdition", requirements={"id"="\d+"})
    */
    public function editCours(Request $request, $id)
	{
        $em = $this->getDoctrine()->getManager();
        $cours = $em->getRepository("App:Cours")->find($id);
        
        if ($cours == null) {
			throw new NotFoundHttpException("Ce cours n'existe pas.");
		}

        $form = $this->get('form.factory')->create(CoursType::class, $cours);
        
        if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
                $notif = new Notification();
                $notif->setMessage($this->getUser()->getName()." a mis à jour le cours \"".$cours->getSubject()."\" de la matière ".$cours->getMatiere()->getNom().".");
                $notif->setCategory("info");
                $notif->setIcon("graduation-cap");
                $notif->setRecipient($cours->getMatiere()->getDepartement().substr($cours->getMatiere()->getAnnee(), 0, 1));

                $em->persist($cours);
                $em->persist($notif);

                $log = new Log();
                $log->setLevel("success");
                $log->setMessage("Le cours ".$cours->getSubject()." a été mis à jour avec succès par l'utilisateur ".$this->getUser()->getUsername().".");
                $em->persist($log);

                $em->flush();
                
                $request->getSession()->getFlashBag()->add('info', 'Le cours bien été mis à jour.');
                return $this->redirectToRoute('matiereDetails', array(
                    'id' => $cours->getMatiere()->getId(),
                    'dpt' => $cours->getMatiere()->getDepartement(),
                    'annee' => $cours->getMatiere()->getAnnee()
                ));
            }
            else{
                $log = new Log();
                $log->setLevel("danger");
                $log->setMessage("Échec de la mise à jour du cours ".$cours->getSubject()." par l'utilisateur ".$this->getUser()->getUsername().".");
                $em->persist($log);
                $em->flush();
            }
        }

		return $this->render('app/forms/form_cours.html.twig', array(
            'form'  => $form->createView(),
            'id' => $id,
            'subject' => $cours->getSubject()
		));
    }

    /**
    * @Route("/cours/delete/{id}", name="coursDelete", requirements={"id"="\d+"})
    */
    public function deleteCours(Request $request, $id)
	{
        $em = $this->getDoctrine()->getManager();

        if(!$this->get('security.authorization_checker')->isGranted('ROLE_MODERATEUR')) {
            $notif = new Notification();
            $notif->setMessage($this->getUser()->getName()." a tenté de supprimer un cours sans y être autorisé.");
            $notif->setCategory("warning");
            $notif->setIcon("alert-polygon");
            $notif->setRecipient("ROLE_ADMIN");

            $em->persist($notif);

            $log = new Log();
            $log->setLevel("danger");
            $log->setMessage("Échec de la suppression du cours ".$cours->getSubject()." par l'utilisateur ".$this->getUser()->getUsername()." sans y être autorisé.");
            $em->persist($log);

            $em->flush();

			throw new AccessDeniedException('Vous n\'avez pas l\'autorisation d\'effectuer cette action ! Un administrateur en est averti.');
        }
        
		$cours = $em->getRepository("App:Cours")->find($id);
        
        if ($cours == null) {
			throw new NotFoundHttpException("Ce cours n'existe pas.");
		}

        $form = $this->get('form.factory')->create();
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $submittedToken = $request->request->get('token');
			if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                $notif = new Notification();
                $notif->setMessage($this->getUser()->getName()." a supprimé le cours \"".$cours->getSubject()."\" de la matière ".$cours->getMatiere()->getNom().".");
                $notif->setCategory("danger");
                $notif->setIcon("graduation-cap");
                $notif->setRecipient($cours->getMatiere()->getDepartement().substr($cours->getMatiere()->getAnnee(), 0, 1));

                $em->remove($cours);
                $em->persist($notif);

                $log = new Log();
                $log->setLevel("success");
                $log->setMessage("Le cours ".$cours->getSubject()." a été supprimé avec succès par l'utilisateur ".$this->getUser()->getUsername().".");
                $em->persist($log);

                $em->flush();
                
                $request->getSession()->getFlashBag()->add('info', 'Le cours a bien été supprimé.');
            }
            else{
                $log = new Log();
                $log->setLevel("danger");
                $log->setMessage("Échec de la suppression du cours ".$cours->getSubject()." par l'utilisateur ".$this->getUser()->getUsername().".");
                $em->persist($log);
                $em->flush();

				$request->getSession()->getFlashBag()->add('danger', 'Une erreur est survenue.');
			}
        }

		return $this->redirectToRoute('matiereDetails', array(
            'id' => $cours->getMatiere()->getId(),
            'dpt' => $cours->getMatiere()->getDepartement(),
            'annee' => $cours->getMatiere()->getAnnee()
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
                $notif->setCategory("success");
                $notif->setIcon("file-text");
                $notif->setRecipient($annale->getMatiere()->getDepartement().substr($annale->getMatiere()->getAnnee(), 0, 1));

                $em->persist($annale);
                $em->persist($notif);

                $log = new Log();
                $log->setLevel("success");
                $log->setMessage("L'annale ".$annale->getSubject()." a été créée avec succès par l'utilisateur ".$this->getUser()->getUsername().".");
                $em->persist($log);

                $em->flush();
                
                $request->getSession()->getFlashBag()->add('info', 'L\'annale a bien été ajoutée.');
                return $this->redirectToRoute('matiereDetails', array(
                    'id' => $annale->getMatiere()->getId(),
                    'dpt' => $annale->getMatiere()->getDepartement(),
                    'annee' => $annale->getMatiere()->getAnnee()
                ));
            }
            else{
                $log = new Log();
                $log->setLevel("danger");
                $log->setMessage("Échec de la création de l'annale ".$annale->getSubject()." par l'utilisateur ".$this->getUser()->getUsername().".");
                $em->persist($log);
                $em->flush();
            }
        }

		return $this->render('app/forms/form_annale.html.twig', array(
			'form'  => $form->createView(),
		));
    }

    /**
    * @Route("/annale/edit/{id}", name="annaleEdition", requirements={"id"="\d+"})
    */
    public function editAnnale(Request $request, $id)
	{
        $em = $this->getDoctrine()->getManager();
		$annale = $em->getRepository("App:Annale")->find($id);
        
        if ($annale == null) {
			throw new NotFoundHttpException("Cette annale n'existe pas.");
		}

        $form = $this->get('form.factory')->create(AnnaleType::class, $annale);
        
        if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
                $notif = new Notification();
                $notif->setMessage($this->getUser()->getName()." a mis à jour l'annale \"".$annale->getSubject()."\" de la matière ".$annale->getMatiere()->getNom().".");
                $notif->setCategory("info");
                $notif->setIcon("file-text");
                $notif->setRecipient($annale->getMatiere()->getDepartement().substr($annale->getMatiere()->getAnnee(), 0, 1));

                $em->persist($annale);
                $em->persist($notif);

                $log = new Log();
                $log->setLevel("success");
                $log->setMessage("L'annale ".$annale->getSubject()." a été mise à jour avec succès par l'utilisateur ".$this->getUser()->getUsername().".");
                $em->persist($log);

                $em->flush();
                
                $request->getSession()->getFlashBag()->add('info', 'L\'annale a bien été mise à jour.');
                return $this->redirectToRoute('matiereDetails', array(
                    'id' => $annale->getMatiere()->getId(),
                    'dpt' => $annale->getMatiere()->getDepartement(),
                    'annee' => $annale->getMatiere()->getAnnee()
                ));
            }
            else{
                $log = new Log();
                $log->setLevel("danger");
                $log->setMessage("Échec de la mise à jour de l'annale ".$annale->getSubject()." par l'utilisateur ".$this->getUser()->getUsername().".");
                $em->persist($log);
                $em->flush();
            }
        }

		return $this->render('app/forms/form_annale.html.twig', array(
            'form'  => $form->createView(),
            'id' => $id,
            'subject' => $annale->getSubject()
		));
    }

    /**
    * @Route("/annale/delete/{id}", name="annaleDelete", requirements={"id"="\d+"})
    */
    public function deleteAnnale(Request $request, $id)
	{
        $em = $this->getDoctrine()->getManager();

        if(!$this->get('security.authorization_checker')->isGranted('ROLE_MODERATEUR')) {
            $notif = new Notification();
            $notif->setMessage($this->getUser()->getName()." a tenté de supprimer une annale sans y être autorisé.");
            $notif->setCategory("warning");
            $notif->setIcon("alert-polygon");
            $notif->setRecipient("ROLE_ADMIN");

            $em->persist($notif);

            $log = new Log();
            $log->setLevel("danger");
            $log->setMessage("Échec de la mise à jour de l'annale ".$annale->getSubject()." par l'utilisateur ".$this->getUser()->getUsername()." sans y être autorisé.");
            $em->persist($log);

            $em->flush();
            
			throw new AccessDeniedException('Vous n\'avez pas l\'autorisation d\'effectuer cette action ! Un administrateur en est averti.');
        }
        
		$annale = $em->getRepository("App:Annale")->find($id);
        
        if ($annale == null) {
			throw new NotFoundHttpException("Cette annale n'existe pas.");
		}

        $form = $this->get('form.factory')->create();
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $submittedToken = $request->request->get('token');
			if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                $notif = new Notification();
                $notif->setMessage($this->getUser()->getName()." a supprimé l'annale \"".$annale->getSubject()."\" de la matière ".$annale->getMatiere()->getNom().".");
                $notif->setCategory("danger");
                $notif->setIcon("file-text");
                $notif->setRecipient($annale->getMatiere()->getDepartement().substr($annale->getMatiere()->getAnnee(), 0, 1));

                $em->remove($annale);
                $em->persist($notif);

                $log = new Log();
                $log->setLevel("success");
                $log->setMessage("L'annale ".$annale->getSubject()." a été supprimée avec succès par l'utilisateur ".$this->getUser()->getUsername().".");
                $em->persist($log);

                $em->flush();
                
                $request->getSession()->getFlashBag()->add('info', 'L\'annale a bien été supprimée.');
            }
            else{
                $log = new Log();
                $log->setLevel("danger");
                $log->setMessage("Échec de la suppression de l'annale ".$annale->getSubject()." par l'utilisateur ".$this->getUser()->getUsername().".");
                $em->persist($log);
                $em->flush();

				$request->getSession()->getFlashBag()->add('danger', 'Une erreur est survenue.');
			}
        }

		return $this->redirectToRoute('matiereDetails', array(
            'id' => $annale->getMatiere()->getId(),
            'dpt' => $annale->getMatiere()->getDepartement(),
            'annee' => $annale->getMatiere()->getAnnee()
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
<?php

// src/Controller/UserController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Twig\Environment;

use App\Form\UserEditType;

use App\Entity\User;
use App\Entity\Log;

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

    /**
    * @Route("/notifications", name="notifications")
    */
    public function notifications(){
        $em = $this->getDoctrine()->getManager();

        $username = $this->getUser()->getUsername();
        $usergroup = $this->getUser()->getUsergroup();
        $userRole = $this->getUser()->getRoles()[0];

        $listNotifications = $em->getRepository("App:Notification")->findAllMyNotifications($username, $usergroup, $userRole);
        
        return $this->render('app/acc_notifications.html.twig', array(
            'listNotifications' => $listNotifications
        ));
    }

    /**
     * @Route("/annuaire/{page}", name="annuaire", requirements={"page" = "\d+"}, defaults={"page" = 1})
     */
    public function annuaireDisplay($page){
        $nbPerPage = 15;
        if ($page < 1) {
            throw $this->createNotFoundException('Page "'.$page.'" inexistante.');
        }
        
        $em = $this->getDoctrine()->getManager();
        $listUsers = $em->getRepository("App:User")->getUsers($page, $nbPerPage);

        $nbPages = ceil(count($listUsers)/$nbPerPage);
        if($page > $nbPages){
            throw $this->createNotFoundException('Page "'.$page.'" inexistante.');
        }
        
        return $this->render('app/annuaire.html.twig', array(
            'listUsers' => $listUsers,
            'nbPages' => $nbPages,
            'page' => $page
        ));
    }

    /**
     * @Route("/annuaire/{username}", name="profileDetails")
     */
    public function profileDisplay($username){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("App:User")->findOneByUsername($username);

        if($user === null){
            throw new NotFoundHttpException("Cet utilisateur n'a pas été trouvé.");
        }

        return $this->render('app/profile_detail.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @Route("/parametres", name="parameters")
     */
    public function parametresEdit(Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("App:User")->find($this->getUser()->getUsername());
        $form = $this->createForm(UserEditType::class, $user);

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			return $this->redirectToRoute('login', array('last_username' => $this->getUser()->getUsername()));
        }

        if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			if($form->isSubmitted() && $form->isValid()) {
                $em->persist($user);

                $log = new Log();
                $log->setLevel("success");
                $log->setMessage("Compte de l'utilisateur ".$user->getUsername()." mis à jour avec succès.");
                $em->persist($log);

				$em->flush();

				$request->getSession()->getFlashBag()->add('success', 'Votre compte a bien été mis à jour !');
                return $this->redirectToRoute('account');
            }
            else{
                $log = new Log();
                $log->setLevel("danger");
                $log->setMessage("Échec de la mise à jour du compte de l'utilisateur ".$user->getUsername().".");
                $em->persist($log);

				$em->flush();
            }
        }
        
        return $this->render('app/acc_profilEdit.html.twig', array(
            'user' => $user,
			'form' => $form->createView()
        ));
    }
}
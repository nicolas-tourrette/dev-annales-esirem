<?php

// src/Controller/UserController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
}
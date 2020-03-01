<?php
// src/Controller/SecurityController.php;

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Form\UserType;

use App\Entity\User;


class SecurityController extends AbstractController
{
	public function login(Request $request, AuthenticationUtils $authenticationUtils)
	{
		// Si le visiteur est déjà identifié, on le redirige vers l'accueil
		if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			return $this->redirectToRoute('index');
		}
		
		return $this->render('Security/login.html.twig', array(
			'last_username' => $authenticationUtils->getLastUsername(),
			'error'         => $authenticationUtils->getLastAuthenticationError(),
		));
	}

	/**
	 * @Route("/register", name="register")
	 */
	public function register(Request $request){
		if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			throw new AccessDeniedException('Vous êtes déjà un utilisateur authentifié.');
		}

		$em = $this->getDoctrine()->getManager();
		$user = new User();

		$form = $this->get('form.factory')->create(UserType::class, $user);

		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			if ($form->isValid()) {
				$em->persist($advert);
				$em->flush();

				return $this->redirectToRoute('login', array('last_username' => $user->getUsername()));
			}
		}

		return $this->render('Security/register.html.twig', array(
			'form'  => $form->createView(),
		));
	}
}

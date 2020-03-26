<?php

// src/Controller/AdminController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

use App\Form\NotificationType;
use App\Form\GrantUserType;

use App\Entity\Notification;
use App\Entity\User;
use App\Entity\Log;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController {
    /**
     * @Route("/", name="adminHome")
     */
    public function home(Request $request, MailerInterface $mailer){
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('App:Log');

        $listLogs = $repository->findAll();

        $notification = new Notification();
        $formEnvoiNotifications = $this->createForm(NotificationType::class, $notification);

        $formGrantUser = $this->createForm(GrantUserType::class);

        $formEmail = $this->createFormBuilder()
            ->add('subject', TextType::class, [
                'label' => "Object du mail",
                'required' => false,
                'attr' => array(
                    'class' => "au-input au-input--full"
                ),
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('text', TextareaType::class, [
                'label' => "Corps du mail",
                'required' => false,
                'attr' => array(
                    'class' => "au-input au-input--full"
                ),
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('sendEmail',
                SubmitType::class,
                array(
                    'label' => "Envoyer le mail",
                    'attr' => array(
                        'class' => "au-btn au-btn--block au-btn--green m-b-20"
                    )
                )
            )
			->getForm();

        if ($request->isMethod('POST')) {
			$formEnvoiNotifications->handleRequest($request);
			if ($formEnvoiNotifications->isSubmitted() && $formEnvoiNotifications->isValid() && $formEnvoiNotifications->getClickedButton() === $formEnvoiNotifications->get('sendNotif')) {
                $em->persist($notification);

                $log = new Log();
                $log->setLevel("success");
                $log->setMessage("La notification à ".$notification->getRecipient()." a bien été enregistrée.");
                $em->persist($log);

                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'La notification à '.$notification->getRecipient().' a bien été envoyée.');
                return $this->redirectToRoute("adminHome");
            }
        }

        if ($request->isMethod('POST')) {
			$formGrantUser->handleRequest($request);
			if ($formGrantUser->isSubmitted() && $formGrantUser->isValid() && $formGrantUser->getClickedButton() === $formGrantUser->get('sendGrant')) {
                $user = $em->getRepository("App:User")->findOneByUsername($formGrantUser->getData()['username']);
                $user->setRoles([$formGrantUser->getData()['role']]);
                $em->persist($user);

                $log = new Log();
                $log->setLevel("success");
                $log->setMessage("Le rôle de l'utilisateur ".$user->getUsername()." a bien été mis à jour.");
                $em->persist($log);

                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Le rôle de l\'utilisateur '.$user->getUsername().' a bien été mis à jour.');
                return $this->redirectToRoute("adminHome");
            }
        }

        if ($request->isMethod('POST')) {
			$formEmail->handleRequest($request);
			if ($formEmail->isSubmitted() && $formEmail->isValid() && $formEmail->getClickedButton() === $formEmail->get('sendEmail')) {
                $users = $em->getRepository("App:User")->findAll();

                foreach($users as $user){
                    $message = new TemplatedEmail();

                    $message
                        ->from(new Address('esirem@nicolas-t.ovh', 'Annales ESIREM'))
                        ->to(new Address($user->getEmail(), $user->getName()))
                        //->cc('cc@example.com')
                        //->bcc(new Address('postmaster@nicolas-t.ovh', 'Administrateur Annales ESIREM'))
                        ->replyTo(new Address('postmaster@nicolas-t.ovh', 'No-Reply Annales ESIREM'))
                        //->priority(Email::PRIORITY_HIGH)
                        ->subject('[Annales ESIREM] '.$formEmail->getData()['subject'])
                        ->htmlTemplate('email/advertise.html.twig')
                        ->context(array(
                            'subject' => $formEmail->getData()['subject'],
                            'text' => $formEmail->getData()['text']
                        ))
                    ;

                    $mailer->send($message);
                }

                $message = new TemplatedEmail();

                $message
                    ->from(new Address('esirem@nicolas-t.ovh', 'Annales ESIREM'))
                    ->to(new Address('postmaster@nicolas-t.ovh', 'Administrateur Annales ESIREM'))
                    //->cc('cc@example.com')
                    //->bcc(new Address('postmaster@nicolas-t.ovh', 'Administrateur Annales ESIREM'))
                    ->replyTo(new Address('postmaster@nicolas-t.ovh', 'No-Reply Annales ESIREM'))
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('[Annales ESIREM] '.$formEmail->getData()['subject'])
                    ->htmlTemplate('email/advertise.html.twig')
                    ->context(array(
                        'subject' => $formEmail->getData()['subject'],
                        'text' => $formEmail->getData()['text']
                    ))
                ;

                $mailer->send($message);

                $log = new Log();
                $log->setLevel("success");
                $log->setMessage("Le message \"".$formEmail->getData()['subject']."\" a bien été envoyé à tous les utilisateurs.");
                $em->persist($log);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'L\'e-mail a bien été envoyé à tous les utilisateurs.');
                return $this->redirectToRoute("adminHome");
            }
        }

        return $this->render('admin/home.html.twig', array(
            'listLogs' => $listLogs,
            'formEnvoiNotifications' => $formEnvoiNotifications->createView(),
            'formGrantUser' => $formGrantUser->createView(),
            'formEmail' => $formEmail->createView()
        ));
    }

    /**
     * @Route("/purge/log", name="logPurger")
     */
    public function logPurger(Request $request){
        $em = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->create();
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $submittedToken = $request->request->get('token');
			if ($this->isCsrfTokenValid('purge-log', $submittedToken)) {
                $repository = $em->getRepository('App:Log');

                $listLogs = $repository->findAll();

                foreach($listLogs as $log){
                    $em->remove($log);
                }
                
                $log = new Log();
                $log->setLevel("success");
                $log->setMessage("Le journal a été vidé avec succès par l'utilisateur ".$this->getUser()->getUsername().".");
                $em->persist($log);

                $em->flush();
                
                $request->getSession()->getFlashBag()->add('success', 'Le journal a bien été vidé.');
            }
            else{
                $log = new Log();
                $log->setLevel("danger");
                $log->setMessage("Échec du vidage du journal par l'utilisateur ".$this->getUser()->getUsername().".");
                $em->persist($log);
                $em->flush();

				$request->getSession()->getFlashBag()->add('danger', 'Une erreur est survenue.');
            }
        }

        return $this->redirectToRoute("adminHome");
    }
}
<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

use App\Entity\Notification;
use App\Entity\User;

class NotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message',
                TextType::class,
                array(
                    'label' => "Texte de la notification",
                    'required' => false,
                    'attr' => array(
                        'class' => "au-input au-input--full"
                    ),
                    'constraints' => [
                        new NotBlank()
                    ]
                )
            )
            ->add('icon',
                ChoiceType::class,
                array(
                    'label' => "Icône affichée",
                    'required' => false,
                    'choices' => array(
                        "Alerte" => "alert-triangle",
                        "Aide" => "help",
                        "Information" => "info",
                        "Sécurité" => "shield-security",
                        "Privilège accordé" => "shield-check"
                    ),
                    'attr' => array(
                        'class' => "form-control"
                    ),
                    'constraints' => [
                        new NotBlank()
                    ]
                )
            )
            ->add('category',
                ChoiceType::class,
                array(
                    'label' => "Catégorie",
                    'required' => false,
                    'choices' => array(
                        "Information" => "info",
                        "Succès" => "success",
                        "Avertissement" => "warning",
                        "Critique" => "danger",
                        "Neutre" => "secondary"
                    ),
                    'attr' => array(
                        'class' => "form-control"
                    ),
                    'constraints' => [
                        new NotBlank()
                    ]
                )
            )
            ->add('recipient',
                TextType::class,
                array(
                    'label' => "Destinataire",
                    'required' => false,
                    'attr' => array(
                        'class' => "au-input au-input--full"
                    ),
                    'constraints' => [
                        new NotBlank()
                    ]
                )
            )
            ->add('sendNotif',
                SubmitType::class,
                array(
                    'label' => "Envoyer la notification",
                    'attr' => array(
                        'class' => "au-btn au-btn--block au-btn--green m-b-20"
                    )
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Notification::class,
        ]);
    }
}

<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class GrantUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',
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
            ->add('role',
                ChoiceType::class,
                array(
                    'label' => "Privilèges",
                    'required' => false,
                    'choices' => array(
                        "Utilisateur" => "ROLE_USER",
                        "Modérateur" => "ROLE_MODERATEUR",
                        "Administrateur" => "ROLE_ADMIN"
                    ),
                    'attr' => array(
                        'class' => "form-control"
                    )
                )
            )
            ->add('sendGrant',
                SubmitType::class,
                array(
                    'label' => "Valider le nouveau rôle",
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
            //'data_class' => User::class,
        ]);
    }
}

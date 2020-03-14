<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',
                TextType::class,
                array(
                    'label' => "Identifiant ENT",
                    'required' => true,
                    'attr' => ["placeholder" => "ab123456", 'maxlength' => "8"]
                )
            )
            ->add('password',
                RepeatedType::class,
                array(
                    'type' => PasswordType::class,
                    'label' => "Mot de passe",
                    'required' => true,
                    'options' => ['attr' => ['class' => 'password-field']],
                    'invalid_message' => 'Les  mots de passe doivent correspondre.',
                    'first_options'  => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Confirmer le mot de passe'],
                    'attr' => [ 'class' => "au-input au-input--full" ]
                )

            )
            ->add('name',
                TextType::class,
                array(
                    'label' => 'Nom',
                    'required' => true
                )
            )
            ->add('email',
                EmailType::class,
                array(
                    'label' => 'Adresse e-mail',
                    'required' => true
                )
            )
            ->add('birthday',
                DateType::class,
                array(
                    'label' => "Date de naissance",
                    'required' => true,
                    'widget' => 'single_text',
                )
            )
            ->add('public',
                CheckboxType::class,
                array(
                    'label' => "Rendre mon profil visible dans l'annuaire",
                    'required' => false
                )
            )
            ->add('profilimage',
                TextType::class,
                array(
                    'label' => "Image de profil",
                    'required' => false,
                    'empty_data' => "/img/user.png"
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            // enable/disable CSRF protection for this form
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'create-account'
        ]);
    }
}

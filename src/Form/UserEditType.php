<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\User;

class UserEditType extends AbstractType
{

    public function getParent()
    {
        return UserType::class;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('username')
            ->remove('password')
            ->add('usergroup',
                ChoiceType::class,
                array(
                    'label' => "Groupe",
                    'required' => true,
                    'choices' => array(
                        "1ère année GEIPI" => "GEIPI1",
                        "2ème année GEIPI" => "GEIPI2",
                        "1ère année IE" => "IE1",
                        "2ème année IE" => "IE2",
                        "3ème année IE" => "IE3",
                        "1ère année MDD" => "MDD1",
                        "2ème année MDD" => "MDD2",
                        "3ème année MDD" => "MDD3",
                        "1ère année ROBOTIQUE" => "ROBOTIQUE1",
                        "2ème année ROBOTIQUE" => "ROBOTIQUE2",
                        "3ème année ROBOTIQUE" => "ROBOTIQUE3",
                    )
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
            'csrf_token_id'   => 'update-account'
        ]);
    }
}

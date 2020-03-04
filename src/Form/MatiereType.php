<?php

namespace App\Form;

use App\Entity\Matiere;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatiereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('departement',
                ChoiceType::class,
                array(
                    'label' => "Département",
                    'choices' => array(
                        "Cycle préparatoire GEIPI" => "GEIPI",
                        "Département Informatique/Électronique" => "IE",
                        "Département Matériaux-Développement Durable" => "MDD",
                        "Département Robotique" => "ROBOTIQUE"
                    )
                )
            )
            ->add('annee',
                ChoiceType::class,
                array(
                    'label' => "Année d'étude",
                    'choices' => array(
                        "1ère Année" => "1A",
                        "2ème Année" => "2A",
                        "3ème Année" => "3A"
                    )
                )
            )
            ->add('nom',
                TextType::class,
                array(
                    'label' => "Nom de la matière",
                    'required' => true
                )
            )
            ->add('specialite',
                ChoiceType::class,
                array(
                    'label' => "Spécialité",
                    'choices' => array(
                        "Tronc commun" => "Tronc commun",
                        "Spécialité Ingénérie des Logiciels et des Connaissances (ILC)" => "ILC",
                        "Spécialité Sécurité et Qualité des Réseaux (SQR)" => "SQR",
                        "Spécialité Systèmes Embarqués (SE)" => "SE"
                    )
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Matiere::class,
        ]);
    }
}

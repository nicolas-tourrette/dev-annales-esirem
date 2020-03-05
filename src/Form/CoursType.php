<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Matiere;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject',
                TextType::class,
                array(
                    'label' => "Nom du cours",
                    'required' => true
                )
            )
            ->add('universityYear',
                TextType::class,
                array(
                    'label' => "Année universitaire",
                    'required' => true,
                    'attr' => ["placeholder" => "20xx/20xx"]
                )
            )
            ->add('link',
                TextType::class,
                array(
                    'label' => "Lien vers le cours",
                    'required' => true,
                    'attr' => ["placeholder" => "https://link/to/course"]
                )
            )
            ->add('date',
                DateType::class,
                array(
                    'label' => "Date",
                    'required' => true,
                    'widget' => 'single_text'
                )
            )
            ->add('matiere',
                EntityType::class,
                array(
                    'class' => Matiere::class,
                    'choice_label' => function ($matiere) {
                        return $matiere->getNom()." (".$matiere->getDepartement()." - ".$matiere->getAnnee().")";
                    },
                    'label' => "Matière"
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}

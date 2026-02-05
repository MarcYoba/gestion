<?php

namespace App\Form;

use App\Entity\DepenseA;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepenseAType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type', ChoiceType::class,[
                'label' => 'categorie',
                
                'choices' => [
                    'Autres achats ( Tami, marteau, ralonge, etc.)' => 'Autres achats',
                    'charges générales ( Loyer, eau, electricite, etc.)' => 'service exterieur',
                    'Constructions' => 'Constructions',
                    'impôts et taxes' => 'impots et taxes',
                    'Frais d’établissement' => 'Frais etablissement',
                    'Logiciels' => 'Logiciels',
                    'autre charge(Heures supplémentaires, primes,Motivation,Miting etc.)' => 'autre charge',
                    'Voyages et déplacements, deplacement pour versement,seminaire, autre depense ' => 'Voyages',
                ],
                'placeholder' => 'Choisissez une catégorie',
                
            ])
            ->add('description', TextType::class,[
                'attr' => ['class' => 'form-control form-control-user'],
            ])
            ->add('montant', NumberType::class,[
                'attr' => ['class' => 'form-control form-control-user'],
            ])
            ->add('createdAt', DateType::class,[
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'attr' => ['class' => 'form-control form-control-user'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DepenseA::class,
        ]);
    }
}

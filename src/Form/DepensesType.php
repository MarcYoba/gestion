<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\Depenses;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class DepensesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('createdAt',DateType::class,[
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'attr' => ['class' => 'form-control form-control-user'],
            ])
            ->add('type', ChoiceType::class,[
                'label' => 'categorie',
                
                'choices' => [
                    'Autres achats ( Tami, marteau, ralonge, etc.)' => 'Autres achats',
                    'charges générales ( Loyer, eau, electricite, etc.)' => 'service exterieur',
                    'impôts et taxes' => 'impots et taxes',
                    'charges de personnel(Salaires)' => 'charge personnel',
                    'autre charge(Heures supplémentaires, primes,Motivation,Miting etc.)' => 'autre charge',
                    'Voyages et déplacements, deplacement pour versement,seminaire, autre depense ' => 'Voyages',
                ],
                'placeholder' => 'Choisissez une catégorie',
                
            ])
            ->add('description', TextType::class,[
                'attr' => ['class' => 'form-control form-control-user'],
            ])
            ->add('montant', NumberType::class,[
                'attr' => ['class' => 'form-control form-control-user']
            ])
            ->add('imageFile', FileType::class,[
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Image',
                'required' => false,
                
            ])
            // ->add('agence', EntityType::class, [
            //     'class' => Agence::class,
            //     'choice_label' => 'nom',
            //     'label' => 'Agence',
            //     'attr' => ['class' => 'form-control form-control-user'],
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Depenses::class,
        ]);
    }
}

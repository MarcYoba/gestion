<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\Depenses;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('createdAt')
            ->add('type', ChoiceType::class,[
                'label' => 'categorie',
                
                'choices' => [
                    'Autres achats' => 'Autres achats ( Tami, marteau, ralonge, etc.)',
                    'service exterieur' => 'charges générales ( Loyer, eau, electricite, etc.)',
                    'impots et taxes' => 'impôts et taxes',
                    'charge personnel' => 'charges de personnel(Salaires) ',
                    'autre charge' => '(Heures supplémentaires, primes,Motivation,Miting etc.) ',
                    'Voyages' => 'Voyages et déplacements, deplacement pour versement,seminaire, autre depense',
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
            ->add('agence', EntityType::class, [
                'class' => Agence::class,
                'choice_label' => 'name',
                'label' => 'Agence',
                'attr' => ['class' => 'form-control form-control-user'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Depenses::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Remboursement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemboursementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type',TextType::class,[
                'label' => 'Type de dette',
                'attr' => [
                    'placeholder' => 'Type de dette',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('contrat',TextType::class,[
                'label' => 'Numéro de contrat',
                'attr' => [
                    'placeholder' => 'Numéro de contrat',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('etablissement',TextType::class,[
                'label' => 'Nom de l\'établissement créancier',
                'attr' => [
                    'placeholder' => 'Nom de l\'établissement créancier',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('datesignature',DateType::class,[
                'label' => 'Date signature',
                'attr' => ['class' => 'form-control form-control-user'],
                'widget' => 'single_text',
                
            ])
            ->add('montant',NumberType::class,[
                'label' => 'Montant de l\'échéance',
                'attr' => [
                    'placeholder' => 'Montant de l\'échéance',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('dateprelevement',DateType::class,[
                'label' => 'Date prelevement',
                'attr' => ['class' => 'form-control form-control-user'],
                'widget' => 'single_text',
                
            ])
            ->add('comptedebiter',TextType::class,[
                'label' => 'Compte debiter',
                'attr' => [
                    'placeholder' => 'Compte debiter',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('createtAt',DateType::class,[
                'label' => 'Date de creation',
                'attr' => ['class' => 'form-control form-control-user'],
                'widget' => 'single_text',
                
            ])
            ->add('enregistrer',SubmitType::class,[
                'attr' => ['class' => 'btn btn-info btn-user btn-block'],
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Remboursement::class,
        ]);
    }
}

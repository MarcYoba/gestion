<?php

namespace App\Form;

use App\Entity\Clients;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PoussinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('quantite', NumberType::class, [
                'label' => 'quantite',
                'attr' => [
                    'placeholder' => 'Entrez la quantite',
                    'class' => 'form-control form-control-user'
                ]
            ])
        ->add('client', EntityType::class,[
                'class' => Clients::class,
                'choice_label' => 'nom',
                'label' => 'Sélectionner un client',
                'placeholder' => 'Choisissez un client',
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-select',
                    'id' =>'nomclient',
                    'size' =>'4',
                ]
            ])
        ->add('souche', TextTYpe::class, [
                'label' => 'souche',
                'attr' => [
                    'placeholder' => 'Entrez la souche',
                    'class' => 'form-control form-control-user'
                ]
            ])
        ->add('prix', NumberType::class, [
                'label' => 'prix',
                'attr' => [
                    'placeholder' => 'Entrez le prix',
                    'class' => 'form-control form-control-user'
                ]
            ]) 
        ->add('montant', NumberType::class, [
                'label' => 'montant',
                'attr' => [
                    'placeholder' => 'Entrez le montant',
                    'class' => 'form-control form-control-user',
                    'readonly' => true
                ]
            ])  
        ->add('datecommande', DateType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'label' => 'date commande',
                'attr' => [
                    'placeholder' => 'Entrez la date commande',
                    'class' => 'form-control form-control-user'
                ]
            ])
        ->add('datelivaison', DateType::class, [
                'input' => 'datetime_immutable',
                'widget'=> 'single_text',
                'label' => 'date Livaison',
                'attr' => [
                    'placeholder' => 'Entrez la Date Livaison',
                    'class' => 'form-control form-control-user'
                ]
            ])
        ->add('daterapelle', DateType::class, [
                'input' => 'datetime_immutable',
                'widget'=> 'single_text',
                'label' => 'date Rapelle',
                'attr' => [
                    'placeholder' => 'Entrez la date rapelle',
                    'class' => 'form-control form-control-user'
                ]
            ])
        ->add('mobilepay', NumberType::class, [
                'label' => 'Paiement mobile',
                'attr' => [
                    'placeholder' => 'Entrez le Paiement mobile',
                    'class' => 'form-control form-control-user'
                ]
            ])
        ->add('credit', NumberType::class, [
                'label' => 'Credit',
                'attr' => [
                    'placeholder' => 'Entrez le Paiement credit',
                    'class' => 'form-control form-control-user'
                ]
            ])
        ->add('cash', NumberType::class, [
                'label' => 'Cash',
                'attr' => [
                    'placeholder' => 'Entrez le Paiement cash',
                    'class' => 'form-control form-control-user'
                ]
            ])
        ->add('reste', NumberType::class, [
                'label' => 'Reste',
                'attr' => [
                    'placeholder' => 'Entrez le Paiement Reste',
                    'class' => 'form-control form-control-user'
                ]
            ]) 
        ->add('banque', NumberType::class, [
                'label' => 'Banque',
                'attr' => [
                    'placeholder' => 'Entrez le Paiement Reste',
                    'class' => 'form-control form-control-user'
                ]
            ])  
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

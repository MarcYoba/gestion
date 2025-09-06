<?php

namespace App\Form;

use App\Entity\FournisseurA;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FournisseurAType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Entrez le nom du fournisseur',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('adress', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Entrez l\'adresse du fournisseur',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('telephone' ,TextType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => 'Entrez le numéro de téléphone du fournisseur',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('numfacture' , NumberType::class, [
                'label' => 'Numéro de facture',
                'attr' => [
                    'placeholder' => 'Entrez le numéro de facture',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('email' , TextType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Entrez l\'email du fournisseur',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('dateAchat', DateType::class, [
                'label' => 'Date d\'achat',
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'Sélectionnez la date d\'achat',
                    'class' => 'form-control form-control-user',
                    'value' => (new \DateTime())->format('Y-m-d')
                ]
            ])
            ->add('button', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary btn-user btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FournisseurA::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Fournisseur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FournisseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control form-control-user']
            ])
            ->add('telephone', NumberType::class, [
                'label' => 'Téléphone',
                'attr' => ['class' => 'form-control form-control-user']
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'form-control form-control-user']
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control form-control-user']
            ])
            ->add('numfacture', NumberType::class, [
                'label' => 'Numéro de facture',
                'attr' => ['class' => 'form-control form-control-user']
            ])
            ->add('datefacture', DateType::class, [
                'label' => 'Date de facture',
                'attr' => ['class' => 'form-control form-control-user']
            ])
            ->add('createdAt')
            ->add('button', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary btn-user btn-block',
                    'style' => 'margin-top: 1rem;'
                ],
                
            ]);
           
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fournisseur::class,
        ]);
    }
}

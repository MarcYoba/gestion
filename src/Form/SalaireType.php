<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\Employer;
use App\Entity\Salaire;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant',NumberType::class,[
                'label' => 'Montant Net',
                'attr' => [
                    'placeholder' => 'Entrez le Net',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('createdAd',DateType::class,[
                'attr' => ['class' => 'form-control form-control-user'],
                'widget' => 'single_text',
                'input' => 'datetime_immutable'
            ])
            ->add('status',TextType::class,[
                
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Status du paiement'
            ])
            
            ->add('salaireBrut',NumberType::class,[
                'label' => 'Salaire Brut',
                'attr' => [
                    'placeholder' => 'Salaire Brut',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('cotisationSociales',NumberType::class,[
                'label' => 'Cotisations Sociales',
                'attr' => [
                    'placeholder' => 'Cotisations Sociales',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('impots',NumberType::class,[
                'label' => 'Impots',
                'attr' => [
                    'placeholder' => 'Impots',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('type',ChoiceType::class,[
                'choices' => [
                    'CASH'=>'CASH',
                    'CREDIT'=>'CREDIT',
                    'MOBILE'=>'MOBILE',
                    'BANQUE'=>'BANQUE',
                ],
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Type de paiement'
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
            'data_class' => Salaire::class,
        ]);
    }
}

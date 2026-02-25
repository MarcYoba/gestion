<?php

namespace App\Form;

use App\Entity\Employer;
use App\Entity\ProduitA;
use App\Entity\TransfertA;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransfertAType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite',NumberType::class,[
                
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'definir la quantite',
                ]
            ])
            ->add('matricule',NumberType::class,[
                
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'definir matricule',
                    'readonly' => true,
                ]
            ])
            ->add('createtAt',DateType::class,[
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                    'value' => date("Y-m-d"),
                ]
            ])
            ->add('Employer', EntityType::class,[
                'class' => Employer::class,
                'choice_label' => 'nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sélectionner un Employer',
                ],
            ])
            ->add('button',SubmitType::class,[
                'label' => 'Transferer pour validation',
                'attr' => [
                    'class' => 'btn btn-primary btn-user btn-block',
                    'style' => 'margin-top: 1rem;' // Adds spacing to move the button to a new line
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TransfertA::class,
        ]);
    }
}

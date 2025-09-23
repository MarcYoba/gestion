<?php

namespace App\Form;

use App\Entity\Clients;
use App\Entity\Vaccin;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VaccinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sujet',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer le nom du sujet',
                ]
            ])
            ->add('age',NumberType::class,[
                
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer l\'age de l\'animale',
                ]
            ])
            ->add('typeSujet',TextType::class,[
                
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer la race de l\'animale',
                ]
            ])
            ->add('createtAD',DateType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user'
                ],
                'widget'=>'single_text',
            ])
            ->add('dateRapel',DateType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user'
                ],
                'widget'=>'single_text',
            ])
            ->add('dateVaccin',DateType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user'
                ],
                'widget'=>'single_text',
            ])
            ->add('typeVaccin',ChoiceType::class,[
                'placeholder' => 'Selectionner un Type de vaccin',
                'attr' => [
                    'class' => 'form-control form-select',
                ],
                'choices' => [
                    'Complet' => 'Vaccin Complet',
                    'antirabique' => 'Vaccin antirabique',
                    'parvovirose' => 'Vaccin parvovirose',
                    'eurican' => 'Vaccin eurican LR',
                    'L' => 'Vaccin L ',
                ],
            ])
            ->add('montant',NumberType::class,[
                'input' => 'number',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => '0',
                ]
            ])
            ->add('montantNet',NumberType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => '0',
                ]
            ])
            ->add('resteMontant',NumberType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => '0',
                ]
            ])
            ->add('lieux',ChoiceType::class,[
                'placeholder' => 'Inserer le lieux de vaccination',
                'attr' => [
                    'class' => 'form-control form-select',
                ],
                'choices' => [
                    'Entreprise' => 'Entreprise',
                    'Domicille' => 'Domicille',
                ],
            ])
            ->add('client',EntityType::class,[
                'class' => Clients::class,
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionner un client',
                'attr' => [
                    'class' => 'form-control form-select',
                    'id' =>'nomclient',
                    'size' =>'4',
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vaccin::class,
        ]);
    }
}

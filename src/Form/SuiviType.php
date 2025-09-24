<?php

namespace App\Form;

use App\Entity\Clients;
use App\Entity\Suivi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuiviType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer le nom du sujet',
                ]
            ])
            ->add('docteur',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer le nom du sujet',
                ]
            ])
            ->add('jour',DateType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer Jour',
                ],
                'widget'=>'single_text',
            ])
            ->add('observation',TextareaType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer l\'observation',
                ]
            ])
            ->add('conduite',TextareaType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer la conduite',
                ]
            ])
            ->add('montant',NumberType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer le montant',
                ]
            ])
            ->add('createtAd',DateType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user'
                ],
                'widget'=>'single_text',
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
            'data_class' => Suivi::class,
        ]);
    }
}

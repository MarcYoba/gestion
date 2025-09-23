<?php

namespace App\Form;

use App\Entity\Clients;
use App\Entity\Consultation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsultationType extends AbstractType
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
            ->add('age',NumberType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer l\'age',
                ]
            ])
            ->add('sexe',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer le sexe du sujet',
                ]
            ])
            ->add('poid',NumberType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer le poid du sujet',
                ]
            ])
            ->add('esperce',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer l\'esperce du sujet',
                ]
            ])
            ->add('robe',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer le robe du sujet',
                ]
            ])
            ->add('race',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer le race du sujet',
                ]
            ])
            ->add('vaccin',ChoiceType::class,[
                'placeholder' => 'Slectionner le status de vaccin',
                'attr' => [
                    'class' => 'form-control form-select',
                ],
                'choices' => [
                    'oui' => 'oui',
                    'Non' => 'Non',
                ],
            ])
            ->add('vermufuge',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer le race du sujet',
                ]
            ])
            ->add('dateVermufige',DateType::class,[
                'widget'=>'single_text',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Vermufuge',
                ]
            ])
            ->add('regime',TextareaType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer le regime',
                ]
            ])
            ->add('motifConsultation',TextareaType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Motif de la Consultation',
                ]
            ])
            ->add('temperature',NumberType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer la temperature',
                ]
            ])
            ->add('symtome',TextareaType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer lesymtomes',
                ]
            ])
            ->add('dianostique',TextareaType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Presentez votre Dianostique',
                ]
            ])
            ->add('traitement',TextareaType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Prescrire un traitement',
                ]
            ])
            ->add('pronostique',TextareaType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Donne un dianostique',
                ]
            ])
            ->add('prophylaxe',TextareaType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Prophylaxie et Recommandation',
                ]
            ])
            ->add('indication',TextareaType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Presentez un indication',
                ]
            ])
            ->add('examain',TextareaType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Prescrire un examain',
                ]
            ])
            ->add('docteur',TextareaType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Signalture du docteur',
                ]
            ])
            ->add('nomtant',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Montant de facturation',
                ]
            ])
            ->add('createtAd',DateType::class,[
                'widget'=>'single_text',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer la date',
                ]
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
            ->add('dateRappel',DateType::class,[
                'widget'=>'single_text',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer la date',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Autopsie;
use App\Entity\Clients;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutopsieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('famille',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer le famille du sujet',
                ]
            ])
            ->add('espece',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer l\'espece du sujet',
                ]
            ])
            ->add('race',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer la race du sujet',
                ]
            ])
            ->add('age',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer l\'age du sujet',
                ]
            ])
            ->add('origine',TextType::class,['attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer l\'origine du sujet',
                ]])
            ->add('effectif',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer l\'effectif du sujet',
                ]
            ])
            ->add('morbidite',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer la morbidite du sujet',
                ]
            ])
            ->add('mortalite',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer la mortalite du sujet',
                ]
            ])
            ->add('clinique',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer le clinique du sujet',
                ]
            ])
            ->add('traitement',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer le traitement du sujet',
                ]
            ])
            ->add('pathologiques',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer la pathologie du sujet',
                ]
            ])
            ->add('antecedent',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => ' antecedent du sujet',
                ]
            ])
            ->add('vaccinations',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => ' vaccination du sujet',
                ]
            ])
            ->add('embonpoint',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer le embonpoint du sujet',
                ]
            ])
            ->add('mort',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Mort',
                ]
            ])
            ->add('datemort',DateType::class,[
                'widget'=>'single_text',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer la date',
                ]
            ])
            ->add('Lieu',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer le lieu autopsie',
                ]
            ])
            ->add('conservation',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'conservation',
                ]
            ])
            ->add('durreconservation',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Durre conservation',
                ]
            ])
            ->add('dateautopsie',DateType::class,[
                'widget'=>'single_text',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer la date',
                ]
            ])
            ->add('medecin',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Mdecin legiste',
                ]
            ])
            ->add('appendices',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'appendices du sujet',
                ]
            ])
            ->add('muqueuses',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Muqueuses du sujet',
                ]
            ])
            ->add('peau',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'La peau du sujet',
                ]
            ])
            ->add('membre',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'membre du sujet',
                ]
            ])
            ->add('anomalies',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Inserer l\'anomalies du sujet',
                ]
            ])
            ->add('tissu',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Tissu du sujet',
                ]
            ])
            ->add('tube',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Tube digestif',
                ]
            ])
            ->add('respiratoire',TextType::class,[

                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Respiration du sujet',
                ]
            ])
            ->add('circulatoire',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Circulation du sujet',
                ]
            ])
            ->add('genital',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'genital du sujet',
                ]
            ])
            ->add('urinaire',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'urinaire du sujet',
                ]
            ])
            ->add('locomoteur',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Locomoteur',
                ]
            ])
            ->add('nerveux',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Nerveux',
                ]
            ])
            ->add('endocrines',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Endocrines',
                ]
            ])
            ->add('glandes',TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Glandes',
                ]
            ])
            ->add('hemato',TextType::class,['attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Hemato',
                ]])
            ->add('diagnostic',TextareaType::class,['attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Diagnostic',
                ]])
            ->add('certitude',TextareaType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Certitudes',
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Autopsie::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Prospection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProspectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'label' => 'Nom du propect',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('telephone',TextType::class,[
                'label' => 'Numereau de telephone',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('localisation',TextType::class,[
               'label' => ' localisation ',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ] 
            ])
            ->add('speculation',TextType::class,[
                'label' => 'speculation ',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ] 
            ])
            ->add('sujet',TextType::class,[
                'label' => 'Nombre de sujets ',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('souche',TextType::class,[
                'label' => 'Souche / espèce ',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('ravitallement',TextType::class,[
                'label' => 'Source de ravitaillement ',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('commentaire',TextareaType::class,[
                'label' => 'Commentaire ',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('createtAt',DateType::class,[
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prospection::class,
        ]);
    }
}

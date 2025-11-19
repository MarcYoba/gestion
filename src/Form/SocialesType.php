<?php

namespace App\Form;

use App\Entity\Sociales;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocialesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'label' => 'Nom ou raison social',
                'attr' => [
                    'placeholder' => 'Entrez le Nom',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('numeroCotisation',TextType::class,[
                'label' => 'Numero de cotisation sociale',
                'attr' => [
                    'placeholder' => 'Numero de cotisation siciale',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('profession',TextType::class,[
                'label' => 'Profession',
                'attr' => [
                    'placeholder' => 'Entrez le Nom',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('cni',TextType::class,[
                'label' => 'Numero de carte d\'identite',
                'attr' => [
                    'placeholder' => 'Numero de carte d\'identite',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('du',TextType::class,[
                'label' => 'Date de delivrance',
                'attr' => [
                    'placeholder' => 'Date de delivrance',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('ville',TextType::class,[
                'label' => 'Leux de delivrance',
                'attr' => [
                    'placeholder' => 'Leux de delivrance',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('parent',TextType::class,[
                'label' => 'Nom des parents',
                'attr' => [
                    'placeholder' => 'Nom des parents',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('identite',TextType::class,[
                'label' => 'Numero de carte d\'identite',
                'attr' => [
                    'placeholder' => 'Numero de carte d\'identite',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('delivree',TextType::class,[
                'label' => 'Date de delivrance',
                'attr' => [
                    'placeholder' => 'Date de delivrance',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('city',TextType::class,[
                'label' => 'Leux de delivrance',
                'attr' => [
                    'placeholder' => 'Leux de delivrance',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('createtAt',DateType::class,[
                'label' => 'Date d\'enregistrement',
                'attr' => ['class' => 'form-control form-control-user'],
                'widget' => 'single_text',
                'input' => 'datetime_immutable'
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
            'data_class' => Sociales::class,
        ]);
    }
}

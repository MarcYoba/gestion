<?php

namespace App\Form;

use App\Entity\Emprunt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type',TextType::class,[
                'label' => 'Type d\'emprunt',
                'attr' => [
                    'placeholder' => 'Type d\'emprunt',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('montant',NumberType::class,[
                'label' => 'Montant emprunt',
                'attr' => [
                    'placeholder' => 'Montant emprunt',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('durre',TextType::class,[
                'label' => 'Duree du credit',
                'attr' => [
                    'placeholder' => 'Duree du credit',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('tauxinteretdebiteur',NumberType::class,[
                'label' => 'Taux d\'interet debiteur ',
                'attr' => [
                    'placeholder' => 'Taux d\'interet debiteur ',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('tauxannueleffectifglobal',NumberType::class,[
                'label' => 'Taux annuel debiteur ',
                'attr' => [
                    'placeholder' => 'Taux annuel debiteur ',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('couttotal',NumberType::class,[
                'label' => 'Cout total ',
                'attr' => [
                    'placeholder' => 'Cout total',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('garantie',TextType::class,[
                'label' => 'Garantie',
                'attr' => [
                    'placeholder' => 'Garantie',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('identitepreteur',TextType::class,[
                'label' => 'Identite du preteur',
                'attr' => [
                    'placeholder' => 'Identite du preteur',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('emprunteur',TextType::class,[
                'label' => 'Identite empreunteur',
                'attr' => [
                    'placeholder' => 'Identite empreunteur',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('createtAt',DateType::class,[
                'attr' => ['class' => 'form-control form-control-user'],
                'widget' => 'single_text',
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
            'data_class' => Emprunt::class,
        ]);
    }
}

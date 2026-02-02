<?php

namespace App\Form;

use App\Entity\Clients;
use App\Entity\Versement;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VersementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant', NumberType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Montant Net en cash'
                ],
                'label' => 'Montant du versement en cash',
            ])
            ->add('Om', NumberType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'OM / MOMO'
                ],
                'label' => 'Montant versement Mobile',
            ])
            ->add('banque', NumberType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Banque Depose en baque'
                ],
                'label' => 'Montant versement bancaire',
            ])
            ->add('createdAd', DateType::class,[
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'value' => (new \DateTime())->format('Y-m-d')
                ],
                'label' => 'Date du versement',
            ])
            ->add('clients', EntityType::class, [
                'class' => Clients::class,
                'choice_label' => 'nom',
                'multiple' => false, // 
                'expanded' => false, //
                'attr' => [
                    'class' => 'form-control form-control-user form-select',
                    'placeholder' => 'client'],
                'label' => 'Sélectionner Le client',
            ])
            ->add('description', TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Description'
                ],
                'label' => 'Instituler du versement',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Versement::class,
        ]);
    }
}

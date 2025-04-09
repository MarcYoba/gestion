<?php

namespace App\Form;

use App\Entity\VersementA;
use App\Entity\Clients;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VersementAType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant', NumberType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Montant']
            ])
            ->add('Om', NumberType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'OM / MOMO']
            ])
            ->add('banque', NumberType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Banque']
            ])
            ->add('createdAt', DateType::class,[
                'input' => 'datetime_immutable',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Date'],
                
            ])
            ->add('client', EntityType::class, [
                'class' => Clients::class,
                'choice_label' => 'nom',
                'multiple' => false, // 
                'expanded' => false, //
                'attr' => [
                    'class' => 'form-control form-control-user form-select',
                    'placeholder' => 'client'],
                'label' => 'Sélectionner un utilisateur',
            ])
            ->add('description', TextType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Description']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VersementA::class,
        ]);
    }
}

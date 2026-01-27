<?php

namespace App\Form;

use App\Entity\CaisseA;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaisseAType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant',NumberType::class,[
                'attr' => ['class' => 'form-control form-control-user']
            ])
            ->add('operation',ChoiceType::class,[
                'choices' => [
                    'retour en caisse' => 'retour en caisse',
                    'sortie en caisse' => 'sortie en caisse',
                    'sortie OM MOMO' => 'sortie OM MOMO',
                    'sortie SKAB' => 'sortie poussin',
                    'sortie Banque' => 'sortie Banque',
                ],
                'placeholder' => 'Choisissez une catégorie',
                'attr' => ['class' => 'form-control form-control-user']
            ])
            ->add('motif',TextType::class,[
                'attr' => ['class' => 'form-control form-control-user']
            ])
            ->add('createAt',DateType::class,[
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'value' => (new \DateTime())->format('Y-m-d')
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CaisseA::class,
        ]);
    }
}

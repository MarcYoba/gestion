<?php

namespace App\Form;

use App\Entity\Tresorerie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TresorerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelles',ChoiceType::class,[
                'choices' => [
                    'Capacite d Autofinancement (CAFG)' => 'FA',
                   '-Actif circulant HOA' => 'FB',
                    '-Variation des stoks' => 'FC',
                    '-Variation des creances' => 'FD',
                    '+Variation du passif circulant' => 'FE',
                    '-Decaissements lies aux acquisitions d\'immobilisations incorporelles' => 'FF',
                    '-Decaissements lies aux acquisitions d\'immobilisations corporelles' => 'FG',
                    '-Decaissements lies aux acquisitions d\'immobilisations financieres' => 'FH',
                    '+Encaissement lies aux cessions d\'imnmobilisations incorporelles et corporelles' => 'FI',
                    '+Encaissement lies aux cessions d\'imnmobilisations financieres' => 'FJ',
                    '+Augmentations de capital par apports nouveaux' => 'FK',
                    '+Subventions d\investissement recues' => 'FL',
                    '-Prelevement sur le capital ' => 'FM',
                    '-Dividendes verses ' => 'FN',
                    '+Emprunts ' => 'FO',
                    '+Autre dettes financieres ' => 'FP',
                    '- Remboursement des emprunts et autres dettes financieres ' => 'FQ',
                    '- Remboursement des emprunts et autres dettes financieres ' => 'FQ',
                ],
                'label' => 'libelles',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('net',NumberType::class,[
                'label' => 'Net a payer',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Montant Net',
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
            'data_class' => Tresorerie::class,
        ]);
    }
}

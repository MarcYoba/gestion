<?php

namespace App\Form;

use App\Entity\InventaireCaisseA;
use BcMath\Number;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InventaireCaisseAType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('vente',NumberType::class,[
                'attr' =>[
                'class' => 'form-control',
                    'placeholder' => 'definir la vente',
                    'readonly' => true,
                ],
            ])
            ->add('caisse',NumberType::class,[
                'attr' =>[
                'class' => 'form-control',
                    'placeholder' => 'definir la vente',
                ],
            ])
            ->add('ecart',NumberType::class,[
                'attr' =>[
                'class' => 'form-control',
                    'placeholder' => 'definir des ecarts',
                    'readonly' => true,
                ],
            ])
            ->add('createtAt',DateType::class,[
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('justificatif',TextType::class,[
                'attr' =>[
                'class' => 'form-control',
                    'placeholder' => 'texte justificatif',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InventaireCaisseA::class,
        ]);
    }
}

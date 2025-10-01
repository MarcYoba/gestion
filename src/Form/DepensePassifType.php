<?php

namespace App\Form;

use App\Entity\DepensePassif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepensePassifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant',NumberType::class,[
                'label'=> 'Montant Brut',
                'attr' => [ "class" => "form-control form-control-user",
                    'placeholder'=> "Montant Brut"
                ],
            ])
            ->add('libelle',TextType::class,[
                'label'=> 'Libelle',
                'attr' => [ "class" => "form-control form-control-user",
                'placeholder'=> "Entrer le motif de la depense"
                ],
            ])
            ->add('createtAt',DateType::class,[
                'label'=> 'date',
                'widget'=>'single_text',
                'attr' => [ "class" => "form-control form-control-user",
                    'placeholder'=> "date"
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DepensePassif::class,
        ]);
    }
}

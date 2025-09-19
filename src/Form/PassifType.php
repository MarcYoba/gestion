<?php

namespace App\Form;

use App\Entity\Passif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PassifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle',TextType::class,[
                'label'=> 'Libelle',
                'attr' => [ "class" => "form-control form-control-user",
                'placeholder'=> "Libelle Actif"
                ],
            ])
            ->add('montant',NumberType::class,[
                'label'=> 'Montant Brut',
                'attr' => [ "class" => "form-control form-control-user",
                    'placeholder'=> "Montant Brut"
                ],
            ])
            ->add('created',DateType::class,[
                'label'=> 'date',
                'widget'=>'single_text',
                'attr' => [ "class" => "form-control form-control-user",
                    'placeholder'=> "date"
                ],
            ])
            ->add('REF',TextType::class,[
                'label'=> 'Libelle',
                'attr' => [ "class" => "form-control form-control-user",
                'placeholder'=> "REF des actif"
                ],
            ])
            ->add('cathegorie', ChoiceType::class,[
                'label'=> '',
                'attr' => [ "class" => "form-control form-control-user",
                    'placeholder'=> "Choisir une cathegorie"
                ],
                'choices' => [
                    "CAPITAUX PROPRES ET RESSOURCES ASSIMILEES" => "Capital",
                    "DETTES FINANCIERES ET RESSOURCES ASSIMILEES"=>"DETTES",
                    "RESSOURCES STABLES"=>"stable",
                    "PASSIF CIRCULANT"=>"circulant",
                    "TRESORERIE-PASSIF"=>"TRESORERIE",
                    "Ecart de conversion-Passif"=> "Ecart",
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Passif::class,
        ]);
    }
}

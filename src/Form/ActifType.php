<?php

namespace App\Form;

use App\Entity\Actif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActifType extends AbstractType
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
            ->add('brut', NumberType::class,[
                'label'=> 'Montant Brut',
                'attr' => [ "class" => "form-control form-control-user",
                    'placeholder'=> "Montant Brut"
                ],
            ])
            ->add('amortissement',NumberType::class,[
                'label'=> 'amortisement/ prov',
                'attr' => [ "class" => "form-control form-control-user",
                    'placeholder'=> "amortisement/ prov"
                ],
            ])
            ->add('net',NumberType::class,[
                'label'=> 'Montant net',
                
                'attr' => [ "class" => "form-control form-control-user",
                    'placeholder'=> "Montant net"
                ],
            ])
            ->add('created', DateType::class,[
                'label'=> 'date',
                'input' => 'datetime_immutable',
                'widget'=>'single_text',
                'attr' => [ "class" => "form-control form-control-user",
                    'placeholder'=> "date"
                ],
            ])
            ->add('cathegorie',ChoiceType::class,[
                'label'=> '',
                'attr' => [ "class" => "form-control form-control-user",
                    'placeholder'=> "Choisir une cathegorie"
                ],
                'choices' => [
                     "Incorporelles"=>"Immobilisations Incorporelles",
                     "corporelles"=>"Immobilisations corporelles",
                     "financieres"=>"Immobilisations financières",
                     "circulant"=>"ACTIF CIRCULANT",
                     "trsorerieactif"=>"TRESORERIE-ACTIF",
                     "differentiels"=>"produits différentiels",
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Actif::class,
        ]);
    }
}

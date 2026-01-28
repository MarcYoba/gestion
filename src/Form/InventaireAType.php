<?php

namespace App\Form;

use App\Entity\InventaireA;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InventaireAType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite',NumberType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'definir la quantite',
                    'readonly' => true,
                ]
            ])
            ->add('ecart',NumberType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'difference',
                    'readonly' => true,
                ]
            ])
            ->add('inventaire',NumberType::class,[
                
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'definir l\'inventaire',
                ]
            ])
            ->add('produit',EntityType::class,[
                'class' => 'App\Entity\ProduitA',
                'choice_label' => 'nom',
                
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sélectionner un produit',
                ],
            ])
            ->add('createtAt',DateType::class,[
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('button',SubmitType::class,[
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary btn-user btn-block',
                    'style' => 'margin-top: 1rem;' // Adds spacing to move the button to a new line
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InventaireA::class,
        ]);
    }
}

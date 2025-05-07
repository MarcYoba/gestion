<?php

namespace App\Form;

use App\Entity\VenteA;
use App\Entity\Clients;
use App\Entity\ProduitA;
use App\Entity\Agence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VenteAType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('client', EntityType::class,[
            'class' => Clients::class,
            'choice_label' => 'nom',
            'placeholder' => 'Sélectionner un fournisseur',
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ->add('produit', EntityType::class,[
            'class' => ProduitA::class,
            'choice_label' => 'nom',
            'placeholder' => 'Sélectionner un fournisseur',
            'attr' => [
                'class' => 'form-control',
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VenteA::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Achat;
use App\Entity\Fournisseur;
use App\Entity\Produit;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AchatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fournisseur', EntityType::class,[
                'class' => Fournisseur::class,
                'choice_label' => 'nom',
                'label' => 'Fournisseur'
            ]
            )
            ->add('produit', EntityType::class,[
                'class' => Produit::class,
                'choice_label' => 'nom'
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Achat::class,
        ]);
    }
}

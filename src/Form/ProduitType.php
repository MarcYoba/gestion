<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label' => 'Nom du produit',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('prixvente', NumberType::class,[
                'label' => 'Prix de vente',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('quantite', NumberType::class,[
                'label' => 'Quantité',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('stockdebut', NumberType::class,[
                'label' => 'Stock de début',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('cathegorie',ChoiceType::class,[
                'choices' => [
                    'ENERGIE' => 'ENERGIE',
                   ' PROTEINE' => 'PROTEINE',
                    'VITAMINE' => 'VITAMINE',
                    'CONCENTRER' => 'CONCENTRER',
                    'MINERAUX' => 'MINERAUX',
                    'ANTITOXINE' => 'ANTITOXINE',
                    'Autre Produit' => 'Autre Produit',
                ],
                'label' => 'Catégorie',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('createdAt',DateType::class,[
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('type',ChoiceType::class,[
                'choices' => [
                    'Provenderie' => 'Provenderie',
                ],
                'label' => 'Type',
                'attr' => [
                    'class' => 'form-control form-control-user'
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
            'data_class' => Produit::class,
        ]);
    }
}

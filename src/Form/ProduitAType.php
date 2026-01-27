<?php

namespace App\Form;

use App\Entity\ProduitA;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitAType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Nom du produit'
                ]
            ])
            ->add('prixvente', NumberType::class,[
                'label' => 'Prix de vente',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Prix de vente'
                ]
            ])
            ->add('prixachat', NumberType::class,[
                'label' => 'Prix d\'achat',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Prix d\'achat'
                ]
            ])
            ->add('quantite', NumberType::class,[
                'label' => 'Quantité',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Quantite',
                    'readonly' => true,
                    'value' => 0,
                ]
            ])
            ->add('cathegorie', ChoiceType::class,[
                'choices' =>[
                    "VITAMINE VOLAILLE" => "VITAMINE VOLAILLE",
                    "MINERAUX VOLAILLE" => "MINÉRAUX VOLAILLE",
                    "ANTIBIOTIQUE VOLAILLE" => "ANTIBIOTIQUE VOLAILLE",
                    "ANTICOCCIDIEN" => "ANTICOCCIDIEN",
                    "ANTI_INFLAMMATOIRE" => "ANTI-INFLAMMATOIRE",
                    "VERMIFUGE VOLAILLE" => "VERMIFUGE VOLAILLE",
                    "VERMIFUGE INJECTABLE" => "VERMIFUGE INJECTABLE",
                    "VERMIFUGE COMPRIMÉ" => "VERMIFUGE COMPRIMÉ",
                    "DIURETIQUE VOLAILLE" => "DIURÉTIQUE VOLAILLE",
                    "ANTISTRESS VOLAILLE" => "ANTISTRESS VOLAILLE",
                    "DESINFECTANT" => "DÉSINFECTANT",
                    "ANTIBIOTIQUE INJECTABLE" => "ANTIBIOTIQUE INJECTABLE",
                    "PRODUIT POISSON" => "PRODUIT POISSON",
                    "ANTIBIOTIQUE USAGE EXTERNE" => "ANTIBIOTIQUE USAGE EXTERNE",
                    "COMPLEMENT ALIMENTAIRE" => "COMPLÉMENT ALIMENTAIRE",
                    "VITAMINE INJECTABLE" => "VITAMINE INJECTABLE",
                    "MINERAUX INJECTABLE" => "MINÉRAUX INJECTABLE",
                    "PRODUIT NETTOYAGE" => "PRODUIT NETTOYAGE",
                    "VACCIN VOLAILLE" => "VACCIN VOLAILLE",
                    "VACCIN PORCIN" => "VACCIN PORCIN",
                    "VACCIN CANIN" => "VACCIN CANIN",
                    "ACCESSOIRES CHIEN" => "ACCESSOIRES CHIEN",
                    "ACCESSOIRES INCUBATEUR" => "ACCESSOIRES INCUBATEUR",
                    "EQUIPEMENT ELEVAGE" => "ÉQUIPEMENT ÉLEVAGE",
                    "AUTRE" => "AUTRE",
                ],
                'label' => 'Catégorie',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('createdAt', DateType::class,[
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Date de création',
                    'value' => (new \DateTime())->format('Y-m-d')
                ],
            ])
            ->add('type', ChoiceType::class,[
                'choices' => [
                    'Cabinet' => 'Cabinet',
                ],
                'label' => 'Type',
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('button',SubmitType::class,[
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-success btn-user btn-block',
                    'style' => 'margin-top: 1rem;' // Adds spacing to move the button to a new line
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProduitA::class,
        ]);
    }
}

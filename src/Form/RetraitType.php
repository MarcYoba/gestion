<?php

namespace App\Form;

use App\Entity\Retrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RetraitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant',NumberType::class,[
                
                'label' => 'Montant Net',
                'attr' => [
                    'placeholder' => 'Entrez le Net',
                    'class' => 'form-control form-control-user'
                ]
            
            ])
            ->add('compte',TextType::class,[
                'label' => 'Numero de compte',
                'attr' => [
                    'placeholder' => 'Numero de compte',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('libelle',TextType::class,[
                'label' => 'Libelle de l\'operation',
                'attr' => [
                    'placeholder' => 'Libelle de l\'operation',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('createtAt',DateType::class,[
                'label' => 'Date d\'enregistrement',
                'attr' => ['class' => 'form-control form-control-user'],
                'widget' => 'single_text',
                'input' => 'datetime_immutable'
            ])
            ->add('banque',TextType::class,[
                'label' => 'Libelle de l\'operation',
                'attr' => [
                    'placeholder' => 'Libelle de l\'operation',
                    'class' => 'form-control form-control-user'
                ]
            ])
            ->add('enregistrer',SubmitType::class,[
                'attr' => ['class' => 'btn btn-info btn-user btn-block'],
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Retrait::class,
        ]);
    }
}

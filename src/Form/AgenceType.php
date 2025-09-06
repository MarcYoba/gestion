<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'attr' => ['class' => 'form-control form-control-user']
            ])
            ->add('datecreation', DateType::class,[
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'value' => (new \DateTime())->format('Y-m-d')
                ]
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username', // Remplacez 'nom' par le nom de la propriété contenant le nom dans User
                'label' => 'Sélectionner un utilisateur',
                'placeholder' => 'Choisissez un utilisateur',
                'required' => false,
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('adress', TextareaType::class,[
                'attr' => [
                    'class' => 'form-control form-control-user'
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agence::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\Employer;
use App\Entity\User;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EmployerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('createdAt', DateType::class,[
                'attr' => ['class' => 'form-control form-control-user'],
                'widget' => 'single_text',
                'input' => 'datetime_immutable'
            ])
            ->add('user', EntityType::class,[
                'class' => User::class,
                'choice_label' => 'username',
                'label' => 'Sélectionner un utilisateur',
                'placeholder' => 'Choisissez un utilisateur',
                'required' => true,
                'attr' => [
                    'class' => 'form-select form-control-user'
                ]
            ])
            ->add('agence', EntityType::class,[
                'class' => Agence::class,
                'choice_label' => 'nom',
                'mapped' => true,
                'label' => 'Attribuer l\'agence',
                'placeholder' => 'Attribuer l\'agence',
                'required' => true,
                'attr' => [
                    'class' => 'form-select form-control-user'
                ]
            ])
            ->add('poste', ChoiceType::class,[
                'choices' => [
                    'Employer'=>'ROLE_EMPLOYER',
                    'Manager'=>'ROLE_MANAGER',
                    'Directeur'=>'ROLE_DIRECTEUR',
                    'Comptable'=>'ROLE_COMPTABLE',
                    'Secretaire'=>'ROLE_SECRETAIRE',
                    'Chauffeur'=>'ROLE_CHAUFFEUR',
                    'temporaire'=>'ROLE_TEMPORAIRE',
                ],
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Poste Employé'
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employer::class,
        ]);
    }
}

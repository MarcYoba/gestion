<?php

namespace App\Form;

use App\Entity\Immobilisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImmobilisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitulel',FileType::class,[
                'label' => 'Choisir un fichier Excel',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M', // Taille augmentée pour les fichiers Excel
                        'mimeTypes' => [
                            'application/vnd.ms-excel', // .xls
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                            'application/vnd.oasis.opendocument.spreadsheet', // .ods
                            'application/octet-stream', // Certains fichiers Excel
                            'application/vnd.ms-excel.sheet.macroEnabled.12', // .xlsm
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader un fichier Excel valide (XLS, XLSX)',
                    ])
                ],
                'attr' => [
                    'class' => 'form-control-file form-control form-control-user',
                    'accept' => '.xls,.xlsx'
                ],
                'help' => 'Formats acceptés: .xls, .xlsx (max 10Mo)'
                ])
            // ->add('Classe')
            // ->add('Compte')
            // ->add('libelle')
            // ->add('DateAcquisition')
            // ->add('PrixAcquisition')
            // ->add('CumulN1')
            // ->add('DotationN')
            // ->add('CessionsSorties')
            // ->add('CumulN')
            // ->add('ValeurNetN')
            // ->add('user')
            // ->add('agence')
            ->add('createtAt',DateType::class,[
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control form-control-user'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Immobilisation::class,
        ]);
    }
}

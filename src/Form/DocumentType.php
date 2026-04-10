<?php

namespace App\Form;

use App\Entity\Nature;
use App\Entity\Document;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('fichier',FileType::class,[ 
                'data_class' => null,
                "label"=> "Le document",
                "required"=> true])
            ->add('natures',EntityType::class,[ 
                    'class'=> Nature::class,
                   'choice_label' => "libelle",
                    
                    "required"=> true])
            ->add('isFree')
            ->add('createdAt',BirthdayType::class,[ 
                'data_class' => null,
                "label"=> "Date de publication"])
            
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}

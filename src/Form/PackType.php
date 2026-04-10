<?php

namespace App\Form;

use App\Entity\Packs;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('price')
            ->add('reduction')
            ->add('categories',EntityType::class,[
                'class' => Categorie::class,
                'required' => true,
            // Categorie.libelle et si utilise 'choice_label'=> 'id' je vais avoir l id cad Specialite.id
                'choice_label'=> 'libelle',
            //Comme que c est une collection on ajoute 
                'multiple'=> true,
                'expanded'=>true,
            //avec 'by_reference'=> false ca marche bien sans probleme 
                'by_reference'=> false ] )
                ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Packs::class,
        ]);
    }
}

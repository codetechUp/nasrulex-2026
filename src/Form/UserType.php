<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
       ->add('civilite', ChoiceType::class, [
        'choices' => [
            ''=>'',
            'M.'=>'M.',
            'Mme.'=>'Mme'
        ],
    ])
        ->add('prenom')
        ->add('username')
        ->add('telephone')

        ->add('nom_S')
        ->add('Adresse_S')

        ->add('telephone_S')
        ->add('numero_I_F')
       
        ->add('email')
        ->add('password', PasswordType::class)
        ->add('code')
        ->add('confirm_password',PasswordType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('surname')
            ->add('name')
            ->add('birthDate', DateType::Class, array(
            'widget' => 'choice',
            'years' => range(date('Y'), date('Y') - 100)
            ))
            ->add('email')
            ->add('gender', ChoiceType::class, array(
            'choices'  => array(
                'Femme' => 'Femme',
                'Homme' => 'Homme',
            ),
            ))               
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'nom_fr_fr'
            ])
            ->add('profession', ChoiceType::class, array(
            'choices'  => array(
                'Cadre' => 'Cadre',
                'Employé de la fonction publique' => 'Employé de la fonction publique',
            ),
            ))          
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

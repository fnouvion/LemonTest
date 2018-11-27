<?php

namespace App\CustomType;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MyCustomDateType extends DateType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        // Override: Valeur de la date jusqu'à - 100
        $resolver->setDefault('years', range(date('Y'), date('Y') - 100));
    }
}
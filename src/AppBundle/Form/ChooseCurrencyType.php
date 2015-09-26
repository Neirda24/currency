<?php

namespace AppBundle\Form;

use AppBundle\Entity\Currency;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChooseCurrencyType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currency', 'entity', [
                'class'    => Currency::class,
                'property' => 'code',
                'required' => true,
            ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'choose_currency';
    }
}

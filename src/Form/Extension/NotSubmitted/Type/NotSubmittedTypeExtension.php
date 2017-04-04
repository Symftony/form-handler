<?php

namespace FormHandler\Form\Extension\NotSubmitted\Type;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotSubmittedTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'handler_not_submitted_fatal' => false,
            'handler_not_submitted_data' => null,
        ));

        $resolver->setAllowedTypes('handler_not_submitted_fatal', 'boolean');
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\FormType';
    }
}

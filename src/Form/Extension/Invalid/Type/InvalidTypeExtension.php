<?php


namespace Symftony\FormHandler\Form\Extension\Invalid\Type;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvalidTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'handler_invalid_fatal' => false,
            'handler_invalid_data' => null,
        ));

        $resolver->setAllowedTypes('handler_invalid_fatal', 'boolean');
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\FormType';
    }
}

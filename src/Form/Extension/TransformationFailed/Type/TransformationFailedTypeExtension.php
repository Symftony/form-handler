<?php

namespace FormHandler\Form\Extension\TransformationFailed\Type;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransformationFailedTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        if ($resolver->hasDefault('handler_invalid_fatal')) {
            @trigger_error(sprintf('"%s" cannot be use when ', get_class($this)), E_USER_NOTICE);
        }

        $resolver->setDefaults(array(
            'handler_transformation_failed_fatal' => false,
            'handler_transformation_failed_data' => null,
        ));

        $resolver->setAllowedTypes('handler_transformation_failed_fatal', 'boolean');
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\FormType';
    }
}

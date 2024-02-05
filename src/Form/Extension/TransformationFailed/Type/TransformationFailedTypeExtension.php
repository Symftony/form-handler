<?php
declare(strict_types=1);

namespace Symftony\FormHandler\Form\Extension\TransformationFailed\Type;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransformationFailedTypeExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'handler_transformation_failed' => false,
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            FormType::class
        ];
    }
}

<?php
declare(strict_types=1);

namespace Symftony\FormHandler\Form\Extension\Invalid\Type;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvalidTypeExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'handler_invalid' => false,
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            FormType::class
        ];
    }
}

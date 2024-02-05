<?php
declare(strict_types=1);

namespace Symftony\FormHandler\Form\Extension\NotSubmitted\Type;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotSubmittedTypeExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'handler_not_submitted' => false,
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            FormType::class
        ];
    }
}

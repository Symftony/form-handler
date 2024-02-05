<?php
declare(strict_types=1);

namespace Symftony\FormHandler\Exception;

use Symfony\Component\Form\FormInterface;

/**
 * Class TransformationFailedFormException
 * @package Symftony\FormHandler\Exception
 */
class TransformationFailedFormException extends FormException
{
    public function __construct(FormInterface $form, string $message = 'Transformation form failed.', int $code = 0, \Exception $previous = null)
    {
        parent::__construct($form, $message, $code, $previous);
    }
}

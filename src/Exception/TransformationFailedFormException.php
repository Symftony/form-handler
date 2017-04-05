<?php

namespace Symftony\FormHandler\Exception;

use Symfony\Component\Form\FormInterface;

/**
 * Class TransformationFailedFormException
 * @package Symftony\FormHandler\Exception
 */
class TransformationFailedFormException extends FormException
{
    /**
     * @param FormInterface $form
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(FormInterface $form, $message = 'Transformation form failed.', $code = 0, \Exception $previous = null)
    {
        parent::__construct($form, $message, $code, $previous);
    }
}

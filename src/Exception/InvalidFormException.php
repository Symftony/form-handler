<?php

namespace Symftony\FormHandler\Exception;

use Symfony\Component\Form\FormInterface;

/**
 * Class InvalidFormException
 * @package Symftony\FormHandler\Exception
 */
class InvalidFormException extends FormException
{
    /**
     * @param FormInterface $form
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(FormInterface $form, $message = 'Invalid form.', $code = 0, \Exception $previous = null)
    {
        parent::__construct($form, $message, $code, $previous);
    }
}

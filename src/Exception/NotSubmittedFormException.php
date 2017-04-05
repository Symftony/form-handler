<?php

namespace Symftony\FormHandler\Exception;

use Symfony\Component\Form\FormInterface;

/**
 * Class NotSubmittedFormException
 * @package Symftony\FormHandler\Exception
 */
class NotSubmittedFormException extends FormException
{
    /**
     * @param FormInterface $form
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(FormInterface $form, $message = 'Not submitted form.', $code = 0, \Exception $previous = null)
    {
        parent::__construct($form, $message, $code, $previous);
    }
}

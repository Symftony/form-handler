<?php
declare(strict_types=1);

namespace Symftony\FormHandler\Exception;

use Symfony\Component\Form\FormInterface;

/**
 * Class NotSubmittedFormException
 * @package Symftony\FormHandler\Exception
 */
class NotSubmittedFormException extends FormException
{
    public function __construct(FormInterface $form, string $message = 'Not submitted form.', int $code = 0, \Exception $previous = null)
    {
        parent::__construct($form, $message, $code, $previous);
    }
}

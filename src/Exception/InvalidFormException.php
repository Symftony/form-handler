<?php
declare(strict_types=1);

namespace Symftony\FormHandler\Exception;

use Symfony\Component\Form\FormInterface;

/**
 * Class InvalidFormException
 * @package Symftony\FormHandler\Exception
 */
class InvalidFormException extends FormException
{
    public function __construct(FormInterface $form, string $message = 'Invalid form.', int $code = 0, \Exception $previous = null)
    {
        parent::__construct($form, $message, $code, $previous);
    }
}

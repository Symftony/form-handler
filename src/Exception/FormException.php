<?php
declare(strict_types=1);

namespace Symftony\FormHandler\Exception;

use Symfony\Component\Form\FormInterface;

/**
 * Class FormException
 * @package Symftony\FormHandler\Exception
 */
class FormException extends \RuntimeException
{
    public function __construct(
        protected FormInterface $form,
        string                  $message = 'Form exception.',
        int                     $code = 0,
        \Exception              $previous = null,
    )
    {
        parent::__construct($message, $code, $previous);
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }
}

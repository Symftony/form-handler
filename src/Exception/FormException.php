<?php

namespace FormHandler\Exception;

use Symfony\Component\Form\FormInterface;

/**
 * Class FormException
 * @package FormHandler\Exception
 */
class FormException extends \RuntimeException
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @param FormInterface $form
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(FormInterface $form, $message = 'Form exception.', $code = 0, \Exception $previous = null)
    {
        $this->form = $form;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }
}

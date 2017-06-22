<?php

namespace Symftony\FormHandler;

use Symftony\FormHandler\Exception\InvalidFormException;
use Symftony\FormHandler\Exception\NotSubmittedFormException;
use Symftony\FormHandler\Exception\TransformationFailedFormException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormHandler
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param string $type
     * @param null $name
     * @param null $data
     * @param array $options
     *
     * @return FormInterface
     */
    public function createForm($type, $name = null, $data = null, $options = [])
    {
        if (null !== $name) {
            return $this->formFactory->createNamed($name, $type, $data, $options);
        }

        return $this->formFactory->create($type, $data, $options);
    }

    /**
     * @param $request
     * @param FormInterface $form
     *
     * @return mixed
     *
     * @throws mixed
     */
    public function handleRequest(FormInterface $form, $request = null)
    {
        $form->handleRequest($request);
        $formConfig = $form->getConfig();

        if (!$form->isSubmitted()) {
            if (true === $notSubmitted = $formConfig->getOption('handler_not_submitted', false)) {
                throw new NotSubmittedFormException($form);
            }

            return $notSubmitted;
        }

        $formIsValid = $form->isValid();
        $invalid = $formConfig->getOption('handler_invalid', false);
        if (!$formIsValid && true === $invalid) {
            throw new InvalidFormException($form);
        }

        if ($form->getTransformationFailure()) {
            if (true === $failed = $formConfig->getOption('handler_transformation_failed', false)) {
                throw new TransformationFailedFormException($form);
            }

            return $failed;
        }

        if (!$formIsValid) {
            return $invalid;
        }

        return $form->getData();
    }

    /**
     * @param FormInterface $form
     * @param array $data
     * @param bool $clearMissing
     *
     * @return mixed|null
     */
    public function handleData(FormInterface $form, array $data = [], $clearMissing = true)
    {
        $formConfig = $form->getConfig();
        // Don't auto-submit the form unless at least one field is present.
        if ('' === $form->getName() && count(array_intersect_key($data, $form->all())) <= 0) {
            if (true === $notSubmitted = $formConfig->getOption('handler_not_submitted', false)) {
                throw new NotSubmittedFormException($form);
            }

            return $notSubmitted;
        }

        $form->submit($data, $clearMissing);

        $formIsValid = $form->isValid();
        $invalid = $formConfig->getOption('handler_invalid', false);
        if (!$formIsValid && true === $invalid) {
            throw new InvalidFormException($form);
        }

        if ($form->getTransformationFailure()) {
            if (true === $failed = $formConfig->getOption('handler_transformation_failed', false)) {
                throw new TransformationFailedFormException($form);
            }

            return $failed;
        }

        if (!$formIsValid) {
            return $invalid;
        }

        return $form->getData();
    }
}

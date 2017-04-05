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
            if ($formConfig->getOption('handler_not_submitted_fatal', false)) {
                throw new NotSubmittedFormException($form);
            }

            return $formConfig->getOption('handler_not_submitted_data', null);
        }

        if (!$form->isValid()) {
            if ($formConfig->getOption('handler_invalid_fatal', false)) {
                throw new InvalidFormException($form);
            }

            if ($formConfig->hasOption('handler_invalid_data')) {
                return $formConfig->getOption('handler_invalid_data', null);
            }
        }

        if ($form->getTransformationFailure()) {
            if ($formConfig->getOption('handler_transformation_failed_fatal', false)) {
                throw new TransformationFailedFormException($form);
            }

            return $formConfig->getOption('handler_transformation_failed_data', null);
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
            if ($formConfig->getOption('handler_not_submitted_fatal', false)) {
                throw new NotSubmittedFormException($form);
            }

            return $formConfig->getOption('handler_not_submitted_data', null);
        }

        $form->submit($data, $clearMissing);

        if (!$form->isValid()) {
            if ($formConfig->getOption('handler_invalid_fatal', false)) {
                throw new InvalidFormException($form);
            }

            if ($formConfig->hasOption('handler_invalid_data')) {
                return $formConfig->getOption('handler_invalid_data', null);
            }
        }

        if ($form->getTransformationFailure()) {
            if ($formConfig->getOption('handler_transformation_failed_fatal', false)) {
                throw new TransformationFailedFormException($form);
            }

            return $formConfig->getOption('handler_transformation_failed_data', null);
        }

        return $form->getData();
    }
}

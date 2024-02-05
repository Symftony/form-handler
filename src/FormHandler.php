<?php
declare(strict_types=1);

namespace Symftony\FormHandler;

use Symftony\FormHandler\Exception\InvalidFormException;
use Symftony\FormHandler\Exception\NotSubmittedFormException;
use Symftony\FormHandler\Exception\TransformationFailedFormException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormHandler
{
    protected FormFactoryInterface $formFactory;

    public function setFormFactory(FormFactoryInterface $formFactory): void
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
    public function createForm(string $type, $name = null, $data = null, array $options = []): FormInterface
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
    public function handleRequest(FormInterface $form, $request = null): mixed
    {
        $form->handleRequest($request);
        $formConfig = $form->getConfig();

        if (!$form->isSubmitted() && $formConfig->hasOption('handler_not_submitted')) {
            $notSubmitted = $formConfig->getOption('handler_not_submitted');
            if (true === $notSubmitted) {
                throw new NotSubmittedFormException($form);
            }

            return $notSubmitted;
        }

        return $this->postSubmit($form);
    }

    /**
     * @param FormInterface $form
     * @param array $data
     * @param bool $clearMissing
     *
     * @return mixed|null
     */
    public function handleData(FormInterface $form, array $data = [], bool $clearMissing = true): mixed
    {
        $formConfig = $form->getConfig();
        // Don't auto-submit the form unless at least one field is present.
        if ('' === $form->getName() && count(array_intersect_key($data, $form->all())) <= 0 && $formConfig->hasOption('handler_not_submitted')) {
            $notSubmitted = $formConfig->getOption('handler_not_submitted');
            if (true === $notSubmitted) {
                throw new NotSubmittedFormException($form);
            }

            return $notSubmitted;
        }

        $form->submit($data, $clearMissing);

        return $this->postSubmit($form);
    }

    private function postSubmit(FormInterface $form,)
    {
        $formConfig = $form->getConfig();
        if ($form->isSubmitted()) {
            if (!$form->isValid() && $formConfig->hasOption('handler_invalid')) {
                $invalid = $formConfig->getOption('handler_invalid');
                if (true === $invalid) {
                    throw new InvalidFormException($form);
                }

                return $invalid;
            }

            $transformationFailure = $form->getTransformationFailure();
            if ($transformationFailure && $formConfig->hasOption('handler_transformation_failed')) {
                $failed = $formConfig->getOption('handler_transformation_failed');
                if (true === $failed) {
                    throw new TransformationFailedFormException($form, 'Transformation form failed.', 0, $transformationFailure);
                }

                return $failed;
            }
        }

        return $form->getData();
    }
}

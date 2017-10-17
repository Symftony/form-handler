<?php

namespace Example;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Choice;
use Symftony\FormHandler\FormHandler;

class CustomFormHandler extends FormHandler
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function formChoice()
    {
        return $this->createForm(ChoiceType::class, 'my-value', null, [
            'method' => 'GET',
            'choices' => [
                'FOO' => 'FOO',
                'BAR' => 'BAR  (invalid choice)',
            ],
            'constraints' => new Choice([
                'choices' => [
                    'FOO',
                ]
            ]),
            'handler_invalid' => 'invalid return data',
            'handler_not_submitted' => 'not submitted return data',
            'handler_transformation_failed' => 'transformation fail return data',
        ]);
    }

    public function createFromRequest($request = null, $notSubmitted = true, $invalid = true)
    {
        $form = $this->createForm(ChoiceType::class, 'my-value', null, [
            'method' => 'GET',
            'choices' => [
                'FOO' => 'FOO',
                'BAR' => 'BAR  (invalid choice)',
            ],
            'constraints' => new Choice([
                'choices' => [
                    'FOO',
                ]
            ]),
            'handler_invalid' => $invalid,
            'handler_not_submitted' => $notSubmitted,
        ]);

        return $this->handleRequest($form, $request);
    }
}
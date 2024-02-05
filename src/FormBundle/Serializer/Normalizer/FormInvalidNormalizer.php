<?php
/*
 * https://github.com/FriendsOfSymfony/FOSRestBundle/blob/3.x/Serializer/Normalizer/FormErrorNormalizer.php
 * This file is part of the FOSRestBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symftony\FormHandler\FormBundle\Serializer\Normalizer;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Normalizes invalid Form instances.
 *
 * @author Ener-Getick <egetick@gmail.com>
 *
 * @codeCoverageIgnore
 */
class FormInvalidNormalizer implements NormalizerInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator
    )
    {
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof FormInterface && $data->isSubmitted() && !$data->isValid();
    }

    public function normalize($object, $format = null, array $context = []): float|int|bool|\ArrayObject|array|string|null
    {
        return [
            'code' => $context['status_code'] ?? null,
            'message' => 'Validation Failed',
            'errors' => $this->convertFormToArray($object),
        ];
    }

    /**
     * This code has been taken from JMSSerializer.
     *
     * @param FormInterface $data
     *
     * @return array
     */
    private function convertFormToArray(FormInterface $data): array
    {
        $form = $errors = [];
        foreach ($data->getErrors() as $error) {
            $errors[] = $this->getErrorMessage($error);
        }
        if ($errors) {
            $form['errors'] = $errors;
        }
        $children = [];
        foreach ($data->all() as $child) {
            if ($child instanceof FormInterface) {
                $children[$child->getName()] = $this->convertFormToArray($child);
            }
        }
        if ($children) {
            $form['children'] = $children;
        }
        return $form;
    }

    /**
     * @param FormError $error
     *
     * @return string
     */
    private function getErrorMessage(FormError $error)
    {
        if (null !== $error->getMessagePluralization()) {
            return $this->translator->transChoice($error->getMessageTemplate(), $error->getMessagePluralization(), $error->getMessageParameters(), 'validators');
        }
        return $this->translator->trans($error->getMessageTemplate(), $error->getMessageParameters(), 'validators');
    }

}

<?php

namespace Example;

require_once __DIR__ . '/../vendor/autoload.php';
require_once 'CustomFormHandler.php';
use Symftony\FormHandler\Exception\FormException;
use Symftony\FormHandler\Form\Extension\Invalid\Type\InvalidTypeExtension;
use Symftony\FormHandler\Form\Extension\NotSubmitted\Type\NotSubmittedTypeExtension;
use Symftony\FormHandler\Form\Extension\TransformationFailed\Type\TransformationFailedTypeExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\Validator\Validation;

// Initialize form component
// add not submitted type extension
// add invalid type extension
// /!\ DON'T forget to add validator extension
$formFactory = Forms::createFormFactoryBuilder()
    ->addTypeExtension(new NotSubmittedTypeExtension())
    ->addTypeExtension(new InvalidTypeExtension())
    ->addTypeExtension(new TransformationFailedTypeExtension())
    ->addExtension(new ValidatorExtension(Validation::createValidator()))
    ->getFormFactory();

// Initialize form handler
$formHandler = new CustomFormHandler();
$formHandler->setFormFactory($formFactory);

// Create the form
$form = $formHandler->formChoice();
// Handle request
$exception = null;
try {
    $result = $formHandler->handleRequest($form);
} catch (FormException $e) {
    $exception = $e;
}
?>
<html>
<head>
    <title>Form handler throw NotSubmitted/Invalid Exception example</title>
</head>
<body>
<?php include 'menu.php'; ?>
<div class="container">
    <h1>Form handler will return default data when exception append</h1>
    <div class="content">
        <p>Form handler will return configured default data when not submit/invalid form</p>
        <p>FOO choice : the form handler will return 'FOO' ($form->getData())</p>
        <p>BAR choice : the form handler will return 'invalid return data'</p>
        <p>BAZ choice : the form handler will return 'invalid return data'</p>
        <p class="important">/!\ The "ValidatorExtension" is added to the FormFactory, so the Transformation failed
            become a constraint violation and the InvalidFormException was throw before the
            TransformationFailedException /!\</p>
    </div>
    <div class="content">
        <form method="GET">
            <label>My value
                <select name="my-value">
                    <option value="FOO">FOO</option>
                    <option value="BAR">BAR (invalid choice)</option>
                    <option value="BAZ">BAZ (transformation fail choice)</option>
                </select></label>
            <input type="submit">
            <a href="custom-form-web.php">Reset form</a>
        </form>

        <div class="formdebug">
            <code>$form->isSubmitted() : <?= var_export($form->isSubmitted()) ?></code>
            <code>$form->isValid() : <?= $form->isSubmitted() &&var_export($form->isValid()) ?></code>
            <code>$form->getTransformationFailure() :
                <?php if ($form->getTransformationFailure()): ?>
                    <span class="warning"><?= $form->getTransformationFailure()->getMessage(); ?></span>
                <?php else: ?>
                    null
                <?php endif ?>
            </code>
            <code>$form->getData() : <?= var_export($form->getData()); ?></code>
            <code>$formHandler->handleRequest($form) : <?php if (isset($result)) var_export($result); ?>
                <?php if (null !== $exception): ?>
                    <span class="error"><?= get_class($exception) . ' : ' . $exception->getMessage() ?></span>
                <?php endif ?>
            </code>
        </div>
    </div>
</div>
</body>
</html>

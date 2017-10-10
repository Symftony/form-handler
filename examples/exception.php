<?php

namespace Example;

require_once __DIR__ . '/../vendor/autoload.php';

use Symftony\FormHandler\Exception\FormException;
use Symftony\FormHandler\Form\Extension\NotSubmitted\Type\NotSubmittedTypeExtension;
use Symftony\FormHandler\Form\Extension\TransformationFailed\Type\TransformationFailedTypeExtension;
use Symftony\FormHandler\FormHandler;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Forms;

// Initialize form component
// add not submitted type extension
// add transformation failed type extension
$formFactory = Forms::createFormFactoryBuilder()
    ->addTypeExtension(new NotSubmittedTypeExtension())
    ->addTypeExtension(new TransformationFailedTypeExtension())
    ->getFormFactory();

// Initialize form handler
$formHandler = new FormHandler();
$formHandler->setFormFactory($formFactory);

// Create the form
$form = $formHandler->createForm(ChoiceType::class, 'my-value', null, [
    'method' => 'GET',
    'choices' => [
        'FOO' => 'FOO',
    ],
    'handler_not_submitted' => true,
    'handler_transformation_failed' => true,
]);

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
    <title>Form handler will throw NotSubmitted/TransformationFailed exception</title>
</head>
<body>
<?php include 'menu.php'; ?>
<div class="container">
    <h1>Form handler will throw NotSubmitted/TransformationFailed exception</h1>
    <div class="content">
        <p>Form handler throw an exception when the form is not submitted or tansformation failed.</p>
        <p>FOO choice : the form handler will return 'FOO' ($form->getData())</p>
        <p>BAZ choice not exist in form choice so the form will Throw TransformationFailedException</p>
        <p class="important">/!\ In this example the "ValidatorExtension" is NOT added to the FormFactory, so the TransformationFailedException can be throw /!\</p>
    </div>
    <div class="content">
        <form method="GET">
            <label>My value
                <select name="my-value">
                    <option value="FOO">FOO</option>
                    <option value="BAZ">BAZ (transformation fail choice)</option>
                </select></label>
            <input type="submit">
            <a href="exception.php">Reset form</a>
        </form>

        <div class="formdebug">
            <code>$form->isSubmitted() : <?= var_export($form->isSubmitted()) ?></code>
            <code>$form->isValid() : <?= var_export($form->isValid()) ?></code>
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
                    <span class="error">throw <?= get_class($exception) . ' : ' . $exception->getMessage() ?></span>
                <?php endif ?>
            </code>
        </div>
    </div>
</div>
</body>
</html>

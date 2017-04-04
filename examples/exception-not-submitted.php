<?php

namespace Example;

require_once __DIR__ . '/../vendor/autoload.php';

use FormHandler\Exception\FormException;
use FormHandler\Form\Extension\NotSubmitted\Type\NotSubmittedTypeExtension;
use FormHandler\FormHandler;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Forms;

// Initialize form component
// add not submitted extension
$formFactory = Forms::createFormFactoryBuilder()
    ->addTypeExtension(new NotSubmittedTypeExtension())
    ->getFormFactory();

// Initialize form handler
$formHandler = new FormHandler();
$formHandler->setFormFactory($formFactory);

// Create the form
$form = $formHandler->createForm(ChoiceType::class, 'my-value', null, [
    'method' => 'GET',
    'choices' => [
        'FOO' => 'foo',
        'BAR' => 'bar',
    ],
    'handler_not_submitted_fatal' => true,
]);

// Handle request
$exception = null;
try {
    $formHandler->handleRequest($form);
} catch (FormException $e) {
    $exception = $e;
}
?>
<html>
<head>
    <title>Form handler throw NotSubmitted Exception example</title>
</head>
<body>
<div><p>Form handler throw an exception when the form is not submitted.</p></div>
<?php if (null !== $exception): ?>
    <div style="color:#ff361c;font-weight: bold;"><?php echo sprintf('Exception "%s" with message: %s', get_class($exception), $exception->getMessage()); ?></div>
<?php endif ?>
<div>$form->getTransformationFailure() :
    <?php if ($form->getTransformationFailure()): ?>
        <?= $form->getTransformationFailure()->getMessage(); ?>
    <?php else: ?>
        null
    <?php endif ?>
</div>
<div>$form->isSubmitted() : <?= $form->isSubmitted() ? 'true' : 'false'; ?></div>
<div>$form->isValid() : <?= $form->isValid() ? 'true' : 'false'; ?></div>
<div>$form->getData() : <?= json_encode($form->getData()); ?></div>
<form method="GET">
    <label>My value
        <select name="my-value">
            <option value="foo">FOO</option>
            <option value="bar">BAR</option>
            <option value="baz">BAZ (invalid choice)</option>
        </select></label>
    <input type="submit">
    <a href="exception-not-submitted.php">Reset form</a>
</form>
<?php include 'menu.php'; ?>
</body>
</html>

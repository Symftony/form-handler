<?php

namespace Example;

require_once __DIR__ . '/../vendor/autoload.php';

use Symftony\FormHandler\Exception\FormException;
use Symftony\FormHandler\Form\Extension\Invalid\Type\InvalidTypeExtension;
use Symftony\FormHandler\Form\Extension\NotSubmitted\Type\NotSubmittedTypeExtension;
use Symftony\FormHandler\Form\Extension\TransformationFailed\Type\TransformationFailedTypeExtension;
use Symftony\FormHandler\FormHandler;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Forms;

// Initialize form component
// add not submitted type extension
// add invalid type extension
// /!\ DON'T forget to add validator extension
$formFactory = Forms::createFormFactoryBuilder()
    ->addTypeExtension(new NotSubmittedTypeExtension())
    ->addTypeExtension(new InvalidTypeExtension())
    ->addTypeExtension(new TransformationFailedTypeExtension())
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
    'handler_invalid' => true,
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
    <title>Form handler throw Invalid/NotSubmitted Exception example</title>
</head>
<body>
<div><p>Form handler throw an exception when the form is not submitted or invalid.</p></div>
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
<div>$formHandler->handleRequest($form) : <?= isset($result) ? $result : 'Not initialize'; ?></div>
<div>$form->isSubmitted() : <?= $form->isSubmitted() ? 'true' : 'false'; ?></div>
<div>$form->isValid() : <?= $form->isValid() ? 'true' : 'false'; ?></div>
<div>$form->getData() : <?= json_encode($form->getData()); ?></div>
<form method="GET">
    <label>My value
        <select name="my-value">
            <option value="FOO">FOO</option>
            <option value="BAR">BAR</option>
            <option value="BAZ">BAZ (invalid choice)</option>
        </select></label>
    <input type="submit">
    <a href="exception.php">Reset form</a>
</form>
<?php include 'menu.php'; ?>
</body>
</html>

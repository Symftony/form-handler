<?php

namespace Example;

require_once __DIR__ . '/../vendor/autoload.php';

use Symftony\FormHandler\FormHandler;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Forms;

// Initialize form handler
$formHandler = new FormHandler();
$formHandler->setFormFactory(Forms::createFormFactory());

// Create the form
$form = $formHandler->createForm(TextType::class, 'my-value', 'My default data', ['method' => 'GET']);

// Handler request
$result = $formHandler->handleRequest($form);
?>
<html>
<head>
    <title>Form handler example</title>
</head>
<body>
<div>$formHandler->handleRequest($form) : <?= isset($result) ? $result : 'Not initialize'; ?></div>
<div>$form->isSubmitted() : <?= $form->isSubmitted() ? 'true' : 'false'; ?></div>
<div>$form->isValid() : <?= $form->isValid() ? 'true' : 'false'; ?></div>
<div>$form->getData() : <?= $form->getData(); ?></div>
<form method="GET">
    <label>My value
        <input name="my-value"/></label>
    <input type="submit">
</form>
<?php include 'menu.php'; ?>
</body>
</html>

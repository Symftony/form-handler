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
    <title>Form handler Basic</title>
</head>
<body>
<?php include 'menu.php'; ?>
<div class="container">
    <h1>Form handler Basic</h1>
    <div class="content">
        <form method="GET">
            <label>My value
                <input name="my-value"/></label>
            <input type="submit">
            <a href="index.php">Reset form</a>
        </form>
        <div class="formdebug">
            <code>$form->isSubmitted() : <?= var_export($form->isSubmitted()) ?></code>
            <code>$form->isValid() : <?= $form->isSubmitted() && var_export($form->isValid()) ?></code>
            <code>$form->getData() : <?= var_export($form->getData()); ?></code>
            <code>$formHandler->handleRequest($form) : <?= var_export($result); ?></code>
        </div>
    </div>
</div>
</body>
</html>

# form-handler
Symfony form handler abstraction

## Installation

The recommended way to install FormHandler is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

```bash
php composer require symftony/form-handler
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

## Documentation

This bundle provide a build in FormHandler and TypeExtension.
The TypeExtension add options to the Symfony FormType



## Use in Symfony

### Add the bundle to your kernel `app/AppKernel.php`

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            ...
            new Symftony\FormHandler\FormBundle\FormHandlerBundle(),
            ...
        ];
    }
    ...
```

### Use the built in FormHandler in a controller

```php
    public function yourAction(Request $request)
    {
        $formHandler = $this->get('form_handler.form_handler.default');
        // Throw exception if the form is NotSubmit/NotValid/TransformFailed
        $form = $formHandler->createForm(TextType::class, 'my-value', 'My default data', [
            'method' => 'GET',
            'handler_invalid' => true,
            'handler_not_submitted' => true,
            'handler_transformation_failed' => true,
        ]);
        // You get the $form->getData() if all succeed
        $result = $formHandler->handleRequest($form, $request);

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'result' => $result,
        ]);
    }
```

> You can add a try/catch to handle Exception by yourself 
> OR implement a [KernelException](http://symfony.com/doc/current/event_dispatcher.html)

### Extends the FormHandler

Create `YourFormHandler` and extends `FormHandler`

```php
<?php

namespace AppBundle\Form\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symftony\FormHandler\FormHandler;

class YourFormHandler extends FormHandler
{
    public function createFromRequest(Request $request, $notSubmitted = true, $invalid = true)
    {
        $form = $this->createForm(TextType::class, 'my-value', 'My default data', [
            'handler_not_submitted' => $notSubmitted,
            'handler_invalid' => $invalid,
        ]);

        $result = $this->handleRequest($form, $request);

        // DO whatever you want with your $result
        
        return $result
    }
}
```

Declare as a service 

```

```yaml
services:
    app.form_handler.your:
        class: AppBundle\Form\Handler\YourFormHandler
        tags:
            - { name: 'form.handler' }
```

> /!\ Dont forget to tag with 'form.handler'
> Or to Inject the FormFactory by yourself

Use it!!!

```php
    public function yourAction(Request $request)
    {
        return $this->render('default/index.html.twig', [
            'result' => $this->get('app.form_handler.your')->createFromRequest(
                $request,
                'my data if form wasn\'t post' // result if the form isn't submitted
                true // Gonna throw Exception when the form isn't valid
            ),
        ]);
    }
```
